(function() {
    const defaultConfig = {
        hex_color: "#00f3ff"
    };

    const config = {};
    
    let canvas, ctx;
    let hexagons = [];
    let activeGroups = [];
    let animationId;
    let hexSize = 35;
    let horizontalSpacing, verticalSpacing;
    let isMobile = false;
    let poolSize = 50; // Размер пула гексагонов для переиспользования
    let lastFrameTime = 0;
    let targetFPS = 30; // Целевой FPS для мобильных
    
    // Определяем мобильное устройство
    function detectMobile() {
        return window.innerWidth <= 768 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }
    
    function initCanvas() {
        canvas = document.getElementById('hexCanvas');
        if (!canvas) return;
        
        isMobile = detectMobile();
        
        // На мобильных уменьшаем размер пула и гексагонов
        if (isMobile) {
            poolSize = 30;
            hexSize = 25;
        }
        
        ctx = canvas.getContext('2d');
        initHexagonPool();
        resizeCanvas();
        animate(0);
    }
    
    // Создаем пул гексагонов один раз для переиспользования
    function initHexagonPool() {
        hexagons = [];
        for (let i = 0; i < poolSize; i++) {
            hexagons.push({
                x: 0,
                y: 0,
                size: hexSize,
                row: 0,
                col: 0,
                active: false,
                fadeIn: 0,
                targetX: 0,
                targetY: 0,
                inUse: false
            });
        }
    }
    
    function isInViewport(x, y, size) {
        if (!canvas) return false;
        const margin = size * 2;
        // Учитываем прокрутку страницы
        const scrollY = window.scrollY || window.pageYOffset || 0;
        const viewportTop = scrollY - margin;
        const viewportBottom = scrollY + window.innerHeight + margin;
        
        return x + margin >= 0 && 
               x - margin <= canvas.width && 
               y + margin >= viewportTop && 
               y - margin <= viewportBottom;
    }
    
    function resizeCanvas() {
        if (!canvas) return;
        canvas.width = canvas.offsetWidth;
        // Canvas должен покрывать всю высоту страницы, а не только viewport
        const pageHeight = Math.max(
            document.documentElement.scrollHeight,
            document.body.scrollHeight,
            window.innerHeight
        );
        canvas.height = pageHeight;
        horizontalSpacing = hexSize * Math.sqrt(3);
        verticalSpacing = hexSize * 1.5;
    }
    
    // Получаем свободный гексагон из пула или переиспользуем существующий
    function getHexagonFromPool(x, y) {
        if (!isInViewport(x, y, hexSize)) return null;
        
        // Ищем свободный гексагон в пуле
        let hex = hexagons.find(h => !h.inUse);
        
        if (!hex) {
            // Если все заняты, переиспользуем самый старый неактивный
            hex = hexagons.find(h => !h.active && h.fadeIn === 0);
        }
        
        if (hex) {
            // Обновляем позицию и состояние
            const col = Math.round(x / horizontalSpacing);
            const row = Math.round(y / verticalSpacing);
            const adjustedX = col * horizontalSpacing + (row % 2) * (horizontalSpacing / 2);
            const adjustedY = row * verticalSpacing;
            
            hex.x = adjustedX;
            hex.y = adjustedY;
            hex.targetX = adjustedX;
            hex.targetY = adjustedY;
            hex.row = row;
            hex.col = col;
            hex.inUse = true;
        }
        
        return hex;
    }
    
    function drawHexagon(x, y, size, opacity, color, filled = false) {
        ctx.beginPath();
        for (let i = 0; i < 6; i++) {
            const angle = (Math.PI / 3) * i - Math.PI / 6;
            const hx = x + size * Math.cos(angle);
            const hy = y + size * Math.sin(angle);
            if (i === 0) {
                ctx.moveTo(hx, hy);
            } else {
                ctx.lineTo(hx, hy);
            }
        }
        ctx.closePath();
        
        if (filled) {
            ctx.fillStyle = color;
            ctx.globalAlpha = opacity * 0.5;
            ctx.fill();
            ctx.globalAlpha = 1;
        }
        
        ctx.strokeStyle = color;
        ctx.globalAlpha = opacity;
        ctx.lineWidth = isMobile ? 1.5 : 2; // Тоньше линии на мобильных
        
        // На мобильных упрощаем тени для производительности
        if (!isMobile) {
            ctx.shadowBlur = 10;
            ctx.shadowColor = color;
        }
        ctx.stroke();
        
        if (!isMobile) {
            ctx.shadowBlur = 0;
        }
        
        ctx.globalAlpha = 1;
    }
    
    function getNeighbors(hex) {
        const neighbors = [];
        const patterns = [
            [0, -1], [1, -1], [1, 0], [0, 1], [-1, 1], [-1, 0]
        ];
        
        patterns.forEach(([dc, dr]) => {
            const neighborCol = hex.col + dc;
            const neighborRow = hex.row + dr;
            
            // Проверяем существующий гексагон или создаем новый если в viewport
            let neighbor = hexagons.find(h => h.col === neighborCol && h.row === neighborRow);
            
            if (!neighbor) {
                // Создаем координаты соседа
                const neighborX = neighborCol * horizontalSpacing + (neighborRow % 2) * (horizontalSpacing / 2);
                const neighborY = neighborRow * verticalSpacing;
                
                // Получаем из пула
                neighbor = getHexagonFromPool(neighborX, neighborY);
            }
            
            if (neighbor) {
                neighbors.push(neighbor);
            }
        });
        
        return neighbors;
    }
    
    function activateRandomGroup() {
        // На мобильных создаем меньшие группы
        const groupSize = isMobile ? 12 : 24;
        const maxGroups = isMobile ? 3 : 5;
        
        if (activeGroups.length >= maxGroups) return;
        
        // Учитываем прокрутку страницы для определения видимой области
        const scrollY = window.scrollY || window.pageYOffset || 0;
        const viewportCenterX = canvas.width / 2;
        const viewportCenterY = scrollY + (window.innerHeight / 2);
        
        // Добавляем случайное смещение в пределах видимой области
        const offsetX = (Math.random() - 0.5) * canvas.width * 0.6;
        const offsetY = (Math.random() - 0.5) * window.innerHeight * 0.6;
        
        const startX = viewportCenterX + offsetX;
        const startY = viewportCenterY + offsetY;
        
        const startHex = getHexagonFromPool(startX, startY);
        if (!startHex) return;
        
        const group = [startHex];
        const visited = new Set([startHex]);
        
        while (group.length < groupSize) {
            const current = group[Math.floor(Math.random() * group.length)];
            const neighbors = getNeighbors(current).filter(n => !visited.has(n) && !n.active);
            
            if (neighbors.length === 0) break;
            
            const next = neighbors[Math.floor(Math.random() * neighbors.length)];
            group.push(next);
            visited.add(next);
        }
        
        group.forEach(h => {
            h.active = true;
            h.fadeIn = 0;
        });
        
        activeGroups.push({
            hexes: group,
            lifetime: 0,
            maxLifetime: isMobile ? 2000 : 3000
        });
    }
    
    function cleanupOutOfViewport() {
        // Освобождаем гексагоны, которые вышли за пределы viewport и не активны
        const scrollY = window.scrollY || window.pageYOffset || 0;
        const margin = hexSize * 3; // Больший запас для очистки
        const viewportTop = scrollY - margin;
        const viewportBottom = scrollY + window.innerHeight + margin;
        
        hexagons.forEach(hex => {
            if (!hex.active && hex.fadeIn === 0) {
                // Проверяем с учетом прокрутки
                if (hex.y < viewportTop || hex.y > viewportBottom) {
                    hex.inUse = false;
                    hex.x = 0;
                    hex.y = 0;
                }
            }
        });
    }
    
    function animate(time) {
        if (!canvas || !ctx) return;
        
        // Throttling для мобильных устройств
        if (isMobile) {
            const frameInterval = 1000 / targetFPS;
            const elapsed = time - lastFrameTime;
            if (elapsed < frameInterval) {
                animationId = requestAnimationFrame(animate);
                return;
            }
            lastFrameTime = time - (elapsed % frameInterval);
        }
        
        ctx.fillStyle = '#001221';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        const color = config.hex_color || defaultConfig.hex_color;
        
        // Рисуем только активные гексагоны (те, которые окрашены)
        // На мобильных используем более простую анимацию
        const fadeSpeed = isMobile ? 0.03 : 0.05;
        
        // Учитываем прокрутку при отрисовке
        const scrollY = window.scrollY || window.pageYOffset || 0;
        const viewportTop = scrollY - 200; // Запас сверху
        const viewportBottom = scrollY + window.innerHeight + 200; // Запас снизу
        
        hexagons.forEach(hex => {
            if (hex.inUse && (hex.active || hex.fadeIn > 0)) {
                // Рисуем только гексагоны в видимой области или рядом с ней
                if (hex.y >= viewportTop && hex.y <= viewportBottom) {
                    if (hex.active) {
                        hex.fadeIn = Math.min(1, hex.fadeIn + fadeSpeed);
                    } else {
                        hex.fadeIn = Math.max(0, hex.fadeIn - fadeSpeed);
                        // Освобождаем когда полностью исчез
                        if (hex.fadeIn === 0) {
                            hex.inUse = false;
                        }
                    }
                    drawHexagon(hex.x, hex.y, hex.size, hex.fadeIn, color, true);
                }
            }
        });
        
        // Обновляем группы и удаляем завершенные
        activeGroups = activeGroups.filter(group => {
            group.lifetime += (isMobile ? 33 : 16); // Компенсируем меньший FPS на мобильных
            
            if (group.lifetime > group.maxLifetime) {
                group.hexes.forEach(h => {
                    h.active = false;
                    // Не освобождаем сразу, пусть fadeOut завершится
                });
                return false;
            }
            return true;
        });
        
        // Периодически очищаем гексагоны вне viewport (реже на мобильных)
        if (Math.random() < (isMobile ? 0.005 : 0.01)) {
            cleanupOutOfViewport();
        }
        
        // Активируем новую группу реже на мобильных
        const activationChance = isMobile ? 0.01 : 0.02;
        if (Math.random() < activationChance) {
            activateRandomGroup();
        }
        
        animationId = requestAnimationFrame(animate);
    }
    
    // Initialize when DOM is ready
    function startAnimation() {
        if (document.getElementById('hexCanvas')) {
            // На очень слабых мобильных устройствах можно отключить анимацию
            const isLowEndDevice = isMobile && (
                (navigator.hardwareConcurrency && navigator.hardwareConcurrency <= 2) || 
                (navigator.deviceMemory && navigator.deviceMemory <= 2)
            );
            
            if (isLowEndDevice) {
                // Просто заполняем фон без анимации
                canvas = document.getElementById('hexCanvas');
                if (canvas) {
                    ctx = canvas.getContext('2d');
                    resizeCanvas();
                    ctx.fillStyle = '#001221';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                }
                return;
            }
            
            initCanvas();
        } else {
            // Retry if canvas not ready yet
            setTimeout(startAnimation, 100);
        }
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startAnimation);
    } else {
        startAnimation();
    }
    
    // Handle window resize с оптимизацией для мобильных
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            if (isMobile && canvas && hexagons.length > 0) {
                // На мобильных пересоздаем пул при изменении размера
                initHexagonPool();
            }
            resizeCanvas();
        }, isMobile ? 500 : 250); // Больше задержка на мобильных
    });
    
    // Отслеживаем прокрутку для обновления видимой области
    let scrollTimeout;
    let lastScrollY = 0;
    window.addEventListener('scroll', function() {
        const currentScrollY = window.scrollY || window.pageYOffset || 0;
        const scrollDelta = Math.abs(currentScrollY - lastScrollY);
        
        // Обновляем только при значительной прокрутке (оптимизация)
        if (scrollDelta > 50) {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(function() {
                // Очищаем гексагоны вне видимой области
                cleanupOutOfViewport();
                lastScrollY = currentScrollY;
            }, 150);
        }
    }, { passive: true });
    
    // Cleanup function
    window.hexagonBackgroundCleanup = function() {
        if (animationId) {
            cancelAnimationFrame(animationId);
        }
        window.removeEventListener('resize', resizeCanvas);
    };
})();

