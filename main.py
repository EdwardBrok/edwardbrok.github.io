import requests
from bs4 import BeautifulSoup
import json
import os
import time
import hashlib
import telebot
from telebot import types
from datetime import datetime

# Конфигурация бота
CONFIG = {
    "website_url": "http://127.0.0.1:5500/hac2025/index.html",  # Замените на ваш URL
    "data_file": "articles_data.json",
    "telegram_token": "7899269680:AAGdNnCIE3TbtStQjT8JfLL8lkJDrsGUDJQ",    # Замените на ваш токен
    "channel_id": "-1002372419341",                   # Замените на ID вашего канала
    "check_interval": 300,                       # Интервал проверки (5 минут)
    "admin_ids": [2109893818]                     # ID администраторов для служебных команд
}

# Инициализация бота
bot = telebot.TeleBot(CONFIG['telegram_token'])

def generate_article_id(article_data):
    """Генерирует уникальный ID для статьи"""
    unique_string = f"{article_data.get('title', '')}_{article_data.get('date', '')}"
    return hashlib.md5(unique_string.encode()).hexdigest()

def load_articles():
    """Загружает сохраненные статьи из файла"""
    if os.path.exists(CONFIG['data_file']):
        try:
            with open(CONFIG['data_file'], 'r', encoding='utf-8') as f:
                data = json.load(f)
                # Добавляем ID для старых статей, если их нет
                for article in data:
                    if 'id' not in article:
                        article['id'] = generate_article_id(article)
                return data
        except (json.JSONDecodeError, FileNotFoundError):
            return []
    return []

def save_articles(articles):
    """Сохраняет статьи в файл"""
    with open(CONFIG['data_file'], 'w', encoding='utf-8') as f:
        json.dump(articles, f, ensure_ascii=False, indent=4)

def parse_articles():
    """Парсит статьи с сайта"""
    try:
        headers = {
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
        }
        response = requests.get(CONFIG['website_url'], headers=headers, timeout=15)
        response.raise_for_status()
        
        soup = BeautifulSoup(response.text, 'html.parser')
        articles_section = soup.find('section', id='articles')
        
        if not articles_section:
            print("[Парсер] Секция статей не найдена в HTML")
            return []
            
        articles = []
        
        for article in articles_section.find_all('article', class_='article'):
            try:
                title_elem = article.find('h3', class_='name_news')
                date_elem = article.find('p', class_='date')
                text_elem = article.find('p', class_='news_text')
                link_elem = article.find('a')
                
                if not all([title_elem, date_elem, text_elem]):
                    print("[Парсер] Не найдены обязательные элементы статьи")
                    continue
                
                title = title_elem.get_text(strip=True)
                date_str = date_elem.get_text(strip=True).replace('Опубликовано:', '').strip()
                text = text_elem.get_text(' ', strip=True)
                link = link_elem['href'] if link_elem and 'href' in link_elem.attrs else "#"
                
                # Обработка относительных ссылок
                if link.startswith('/'):
                    base_url = CONFIG['website_url'].rsplit('/', 1)[0]
                    link = f"{base_url}{link}"
                elif not link.startswith(('http://', 'https://')):
                    link = f"{CONFIG['website_url']}/{link}"
                
                article_data = {
                    'title': title,
                    'date': date_str,
                    'text': text,
                    'link': link,
                    'id': generate_article_id({'title': title, 'date': date_str}),
                    'posted_at': datetime.now().strftime('%Y-%m-%d %H:%M:%S')
                }
                
                articles.append(article_data)
                
            except Exception as e:
                print(f"[Парсер] Ошибка обработки статьи: {str(e)}")
                continue
        
        print(f"[Парсер] Найдено {len(articles)} статей")
        return articles
    
    except Exception as e:
        print(f"[Парсер] Критическая ошибка: {str(e)}")
        return []

def check_new_articles():
    """Проверяет и отправляет новые статьи"""
    try:
        saved_articles = load_articles()
        parsed_articles = parse_articles()
        
        saved_ids = {article['id'] for article in saved_articles}
        new_articles = []
        
        for article in parsed_articles:
            if article['id'] not in saved_ids:
                new_articles.append(article)
        
        if new_articles:
            print(f"[Проверка] Найдено {len(new_articles)} новых статей")
            for article in new_articles:
                if send_article_to_channel(article):
                    article['posted'] = True
                else:
                    article['posted'] = False
            
            updated_articles = saved_articles + new_articles
            save_articles(updated_articles)
            
            # Отправляем уведомление админам
            notify_admins(f"Обнаружено {len(new_articles)} новых статей")
            
            return len(new_articles)
        
        print("[Проверка] Новых статей не найдено")
        return 0
    
    except Exception as e:
        print(f"[Проверка] Ошибка: {str(e)}")
        return 0

def send_article_to_channel(article):
    """Отправляет статью в канал"""
    try:
        message = (
            f"<b>{article.get('title', 'Без названия')}</b>\n\n"
            f"<i>📅 {article.get('date', 'Дата не указана')}</i>\n\n"
            f"{article.get('text', '')}\n\n"
            f"<a href='{article.get('link', '#')}'>Читать далее →</a>"
        )
        
        sent_message = bot.send_message(
            chat_id=CONFIG['channel_id'],
            text=message,
            parse_mode='HTML',
            disable_web_page_preview=False
        )
        
        print(f"[Отправка] Статья отправлена: {article.get('title', '')}")
        return True
    
    except Exception as e:
        print(f"[Отправка] Ошибка: {str(e)}")
        return False

def notify_admins(message):
    """Отправляет уведомление администраторам"""
    for admin_id in CONFIG['admin_ids']:
        try:
            bot.send_message(admin_id, message)
        except Exception as e:
            print(f"[Уведомление] Не удалось отправить сообщение admin {admin_id}: {str(e)}")

def start_periodic_checking():
    """Запускает периодическую проверку новых статей"""
    print("[Система] Запуск периодической проверки...")
    while True:
        try:
            print(f"[Проверка] Запуск проверки в {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
            new_count = check_new_articles()
            print(f"[Проверка] Следующая проверка через {CONFIG['check_interval']} сек.")
            time.sleep(CONFIG['check_interval'])
        except Exception as e:
            print(f"[Система] Ошибка в периодической проверке: {str(e)}")
            time.sleep(60)

# ==================== КОМАНДЫ БОТА ====================

@bot.message_handler(commands=['start', 'help'])
def send_welcome(message):
    """Приветственное сообщение"""
    help_text = (
        "🤖 <b>Бот для публикации статей</b>\n\n"
        "Этот бот автоматически публикует новые статьи с сайта в канал.\n\n"
        "<b>Команды:</b>\n"
        "/check - принудительная проверка новых статей\n"
        "/last - показать последние 5 статей\n"
        "/status - статус бота\n"
        "/stats - статистика публикаций\n\n"
        "Для администраторов:\n"
        "/force_check - немедленная проверка (только для админов)"
    )
    bot.reply_to(message, help_text, parse_mode='HTML')

@bot.message_handler(commands=['check'])
def manual_check(message):
    """Ручная проверка статей"""
    bot.reply_to(message, "🔄 Начинаю проверку новых статей...")
    new_count = check_new_articles()
    bot.reply_to(message, f"✅ Проверка завершена. Найдено {new_count} новых статей.")

@bot.message_handler(commands=['last'])
def show_last_articles(message):
    """Показывает последние статьи"""
    articles = load_articles()
    last_articles = articles[-5:] if len(articles) > 5 else articles
    
    if not last_articles:
        bot.reply_to(message, "📭 Нет сохраненных статей.")
        return
    
    response = "📚 <b>Последние статьи:</b>\n\n"
    for idx, article in enumerate(reversed(last_articles), 1):
        response += (
            f"{idx}. <b>{article.get('title', 'Без названия')}</b>\n"
            f"   <i>📅 {article.get('date', 'Дата не указана')}</i>\n"
            f"   {article.get('text', '')[:100]}...\n\n"
        )
    
    bot.reply_to(message, response, parse_mode='HTML')

@bot.message_handler(commands=['status'])
def show_status(message):
    """Показывает статус бота"""
    articles = load_articles()
    last_check = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    status = (
        "📊 <b>Статус бота</b>\n\n"
        f"• Всего статей в базе: <b>{len(articles)}</b>\n"
        f"• Последняя проверка: <b>{last_check}</b>\n"
        f"• Интервал проверки: <b>{CONFIG['check_interval']} сек.</b>\n"
        f"• Канал публикации: <b>{CONFIG['channel_id']}</b>\n"
        f"• Версия бота: <b>1.0</b>"
    )
    bot.reply_to(message, status, parse_mode='HTML')

@bot.message_handler(commands=['stats'])
def show_stats(message):
    """Показывает статистику"""
    articles = load_articles()
    if not articles:
        bot.reply_to(message, "📊 Нет данных для статистики")
        return
    
    last_article = articles[-1]
    stats = (
        "📈 <b>Статистика публикаций</b>\n\n"
        f"• Всего статей: <b>{len(articles)}</b>\n"
        f"• Последняя статья: <b>{last_article.get('title', 'Без названия')}</b>\n"
        f"• Дата публикации: <b>{last_article.get('date', 'Неизвестно')}</b>\n"
        f"• Опубликована: <b>{last_article.get('posted_at', 'Неизвестно')}</b>"
    )
    bot.reply_to(message, stats, parse_mode='HTML')

@bot.message_handler(commands=['force_check'])
def force_check(message):
    """Принудительная проверка (только для админов)"""
    if message.from_user.id not in CONFIG['admin_ids']:
        bot.reply_to(message, "⛔ У вас нет прав для этой команды")
        return
    
    bot.reply_to(message, "⚡ Запуск принудительной проверки...")
    new_count = check_new_articles()
    bot.reply_to(message, f"🔥 Проверка завершена. Найдено {new_count} новых статей.")

# ==================== ЗАПУСК БОТА ====================

if __name__ == '__main__':
    # Инициализация файла данных
    if not os.path.exists(CONFIG['data_file']):
        save_articles([])
        print("[Система] Создан новый файл данных")
    
    # Запуск периодической проверки в отдельном потоке
    try:
        import threading
        check_thread = threading.Thread(target=start_periodic_checking)
        check_thread.daemon = True
        check_thread.start()
        print("[Система] Поток проверки запущен")
    except Exception as e:
        print(f"[Система] Ошибка запуска потока: {str(e)}")
    
    # Запуск бота
    print("[Система] Бот запускается...")
    try:
        notify_admins("🤖 Бот успешно запущен!")
        bot.polling(none_stop=True)
    except Exception as e:
        print(f"[Система] Критическая ошибка: {str(e)}")
        notify_admins(f"‼️ Бот упал с ошибкой: {str(e)}")