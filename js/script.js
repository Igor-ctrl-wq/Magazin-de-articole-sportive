document.addEventListener('DOMContentLoaded', () => {
    initMobileMenu();
    initCartBadge();
    initTheme();
    initLanguage();
    initProductSearch();
});

function initMobileMenu() {
    const hamburger = document.getElementById('hamburger');
    const mobileNav = document.getElementById('mobileNav');

    if (hamburger && mobileNav) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('open');
            mobileNav.classList.toggle('open');
        });
    }
}

function getCos() {
    return JSON.parse(localStorage.getItem('cos') || '[]');
}

function initCartBadge() {
    updateCartBadge();
}

function updateCartBadge() {
    const badge = document.querySelector('.cart-badge');
    if (!badge) return;

    const cos = getCos();

    const total = cos.reduce((sum, item) => {
        if (typeof item === 'number' || typeof item === 'string') {
            return sum + 1;
        }

        return sum + Number(item.qty || 1);
    }, 0);

    badge.textContent = total;
}

function saveCos(cos) {
    localStorage.setItem('cos', JSON.stringify(cos));
    updateCartBadge();
}

function adaugaInCos(id) {
    let cos = getCos();

    cos = cos.map(item => {
        if (typeof item === 'number' || typeof item === 'string') {
            return {
                id: Number(item),
                qty: 1
            };
        }

        return {
            id: Number(item.id),
            qty: Number(item.qty || 1)
        };
    });

    const item = cos.find(p => Number(p.id) === Number(id));

    if (item) {
        item.qty += 1;
    } else {
        cos.push({
            id: Number(id),
            qty: 1
        });
    }

    saveCos(cos);
    alert('Produsul a fost adăugat în coș!');
}

function initTheme() {
    const btn = document.getElementById('themeToggle');
    const savedTheme = localStorage.getItem('theme') || 'light';

    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
    }

    if (btn) {
        btn.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');

            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
        });
    }
}

const translations = {
    ro: {
        "nav.home": "Acasă",
        "nav.products": "Produse",
        "nav.offers": "Oferte",
        "nav.contact": "Contact",
        "nav.login": "Autentificare",
        "nav.register": "Înregistrare",
        "nav.account": "Contul meu",
        "nav.logout": "Logout",
        "search.placeholder": "Caută produse...",

        "hero.title": "Cea mai bună<br><span>calitate</span>",
        "hero.desc": "Articole sportive premium pentru alergare, fitness, fotbal și antrenamente. Alege produse de calitate pentru performanță la orice nivel.",
        "hero.buy": "Cumpără acum",
        "hero.categories": "Vezi categorii",

        "products.title": "Produse recomandate",
        "products.subtitle": "Cele mai populare articole sportive din colecția noastră",
        "products.all": "Toate",
        "products.shoes": "Încălțăminte",
        "products.clothes": "Îmbrăcăminte",
        "products.equipment": "Echipamente",
        "products.accessories": "Accesorii",

        "offers.title": "Oferte speciale",
        "offers.subtitle": "Reduceri și beneficii pentru clienții SportZone.",

        "contact.title": "Contact",
        "contact.subtitle": "Trimite-ne un mesaj și revenim cât mai rapid.",
        "contact.name": "Nume",
        "contact.email": "Email",
        "contact.message": "Mesaj",
        "contact.send": "Trimite mesaj"
    },

    en: {
        "nav.home": "Home",
        "nav.products": "Products",
        "nav.offers": "Offers",
        "nav.contact": "Contact",
        "nav.login": "Login",
        "nav.register": "Register",
        "nav.account": "My account",
        "nav.logout": "Logout",
        "search.placeholder": "Search products...",

        "hero.title": "Best<br><span>quality</span>",
        "hero.desc": "Premium sports items for running, fitness, football and training. Choose quality products for performance at any level.",
        "hero.buy": "Shop now",
        "hero.categories": "View categories",

        "products.title": "Recommended products",
        "products.subtitle": "The most popular sports items from our collection",
        "products.all": "All",
        "products.shoes": "Shoes",
        "products.clothes": "Clothing",
        "products.equipment": "Equipment",
        "products.accessories": "Accessories",

        "offers.title": "Special offers",
        "offers.subtitle": "Discounts and benefits for SportZone customers.",

        "contact.title": "Contact",
        "contact.subtitle": "Send us a message and we will reply as soon as possible.",
        "contact.name": "Name",
        "contact.email": "Email",
        "contact.message": "Message",
        "contact.send": "Send message"
    },

    ru: {
        "nav.home": "Главная",
        "nav.products": "Товары",
        "nav.offers": "Акции",
        "nav.contact": "Контакты",
        "nav.login": "Вход",
        "nav.register": "Регистрация",
        "nav.account": "Мой аккаунт",
        "nav.logout": "Выход",
        "search.placeholder": "Поиск товаров...",

        "hero.title": "Лучшее<br><span>качество</span>",
        "hero.desc": "Премиальные спортивные товары для бега, фитнеса, футбола и тренировок. Выбирай качественные товары для любого уровня.",
        "hero.buy": "Купить сейчас",
        "hero.categories": "Смотреть категории",

        "products.title": "Рекомендуемые товары",
        "products.subtitle": "Самые популярные спортивные товары из нашей коллекции",
        "products.all": "Все",
        "products.shoes": "Обувь",
        "products.clothes": "Одежда",
        "products.equipment": "Оборудование",
        "products.accessories": "Аксессуары",

        "offers.title": "Специальные предложения",
        "offers.subtitle": "Скидки и преимущества для клиентов SportZone.",

        "contact.title": "Контакты",
        "contact.subtitle": "Отправьте нам сообщение, и мы ответим как можно быстрее.",
        "contact.name": "Имя",
        "contact.email": "Email",
        "contact.message": "Сообщение",
        "contact.send": "Отправить сообщение"
    }
};

function initLanguage() {
    const savedLang = localStorage.getItem('lang') || 'ro';

    applyLanguage(savedLang);

    document.querySelectorAll('.lang-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const lang = btn.dataset.lang;

            localStorage.setItem('lang', lang);
            applyLanguage(lang);
        });
    });
}

function applyLanguage(lang) {
    const dict = translations[lang] || translations.ro;

    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.dataset.i18n;

        if (dict[key]) {
            el.innerHTML = dict[key];
        }
    });

    document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
        const key = el.dataset.i18nPlaceholder;

        if (dict[key]) {
            el.placeholder = dict[key];
        }
    });

    document.querySelectorAll('.lang-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.lang === lang);
    });
}

function initProductSearch() {
    const input = document.getElementById('productSearch');
    const cards = document.querySelectorAll('.product-card');

    if (!input || cards.length === 0) return;

    function filterProducts() {
        const q = input.value.toLowerCase().trim();

        cards.forEach(card => {
            const text = card.textContent.toLowerCase();

            if (text.includes(q)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    input.addEventListener('input', filterProducts);

    if (input.value.trim() !== '') {
        filterProducts();
    }
}