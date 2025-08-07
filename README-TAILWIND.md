# Tailwind CSS Integration for Intelescape

Этот проект теперь включает Tailwind CSS для современной стилизации с киберпанк-тематикой.

## Установка

1. **Установите Node.js** (если еще не установлен):
   - Скачайте с [nodejs.org](https://nodejs.org/)

2. **Установите зависимости**:
   ```bash
   npm install
   ```

3. **Соберите CSS**:
   ```bash
   # Для разработки (с автоматическим обновлением)
   npm run dev
   
   # Для продакшена (минифицированный)
   npm run build:prod
   ```

## Использование

### Подключение CSS

Добавьте в ваши PHP файлы подключение Tailwind CSS:

```php
<link href="/view/css/tailwind.css" rel="stylesheet">
```

### Доступные классы

#### Основные цвета
- `bg-cyber-dark-bg` - темный фон
- `bg-cyber-panel-bg` - фон панелей
- `text-cyber-neon-blue` - неоновый синий текст
- `text-cyber-neon-green` - неоновый зеленый текст
- `text-cyber-neon-pink` - неоновый розовый текст

#### Компоненты
- `cyber-panel` - киберпанк панель
- `cyber-button` - киберпанк кнопка
- `cyber-input` - киберпанк поле ввода
- `cyber-card` - киберпанк карточка
- `neon-text` - неоновый текст с анимацией

#### Анимации
- `animate-cyber-pulse` - пульсирующая анимация
- `animate-cyber-glow` - мерцающая анимация
- `animate-fade-in-up` - появление снизу
- `animate-glitch` - эффект глитча

#### Утилиты
- `text-shadow-neon` - неоновая тень текста
- `border-neon` - неоновая граница
- `bg-cyber-gradient` - киберпанк градиент
- `bg-neon-gradient` - неоновый градиент

### Пример использования

```html
<div class="cyber-panel text-center max-w-md mx-auto animate-fade-in-up">
  <h2 class="text-3xl font-bold neon-text animate-cyber-glow">
    Agent Profile
  </h2>
  
  <div class="cyber-card mt-4">
    <div class="cyber-grid grid-cols-3 gap-4">
      <div class="text-center">
        <i class="fas fa-shield-alt text-cyber-neon-blue text-2xl mb-2"></i>
        <div class="text-sm text-gray-400">Status</div>
        <div class="font-bold text-cyber-neon-green">Active</div>
      </div>
    </div>
  </div>
  
  <button class="cyber-button mt-4">
    Accept Mission
  </button>
</div>
```

## Структура файлов

```
├── package.json          # Зависимости и скрипты
├── tailwind.config.js    # Конфигурация Tailwind
├── postcss.config.js     # Конфигурация PostCSS
├── src/
│   └── input.css        # Основной CSS файл
└── view/css/
    └── tailwind.css     # Скомпилированный CSS (создается автоматически)
```

## Кастомизация

### Добавление новых цветов

Отредактируйте `tailwind.config.js`:

```javascript
colors: {
  custom: {
    'new-color': '#ff0000',
  }
}
```

### Добавление новых анимаций

```javascript
animation: {
  'custom-animation': 'custom-keyframes 2s infinite',
},
keyframes: {
  'custom-keyframes': {
    '0%': { opacity: '0' },
    '100%': { opacity: '1' },
  }
}
```

## Команды

- `npm run dev` - Запуск в режиме разработки с автоматическим обновлением
- `npm run build` - Сборка для разработки
- `npm run build:prod` - Сборка для продакшена (минифицированная)

## Примечания

- Все изменения в PHP файлах автоматически отслеживаются
- CSS пересобирается при изменении файлов в папках `views/`, `view/`, `admin/`
- Для продакшена используйте `npm run build:prod` для минификации 