document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.getElementById('hamburger');
    const mobileNav = document.getElementById('mobileNav');

    if (hamburger && mobileNav) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('open');
            mobileNav.classList.toggle('open');
        });
    }

    updateCartBadge();
    initTheme();
    initLanguage();
    initProductSearch();
});

function getCos() {
    return JSON.parse(localStorage.getItem('cos') || '[]');
}

function saveCos(cos) {
    localStorage.setItem('cos', JSON.stringify(cos));
    updateCartBadge();
}

function updateCartBadge() {
    const badge = document.querySelector('.cart-badge');
    if (!badge) return;

    const cos = getCos();
    const total = cos.reduce((sum, item) => sum + (item.qty || 1), 0);
    badge.textContent = total;
}

function adaugaInCos(id) {
    let cos = getCos();
    const item = cos.find(p => Number(p.id) === Number(id));

    if (item) {
        item.qty = (item.qty || 1) + 1;
    } else {
        cos.push({ id: Number(id), qty: 1 });
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
        "logo.tagline": "Echipament de performanță",
        "nav.home": "Acasă",
        "nav.products": "Produse",
        "nav.offers": "Oferte",
        "nav.contact": "Contact",
        "nav.login": "Autentificare",
        "nav.register": "Înregistrare",
        "nav.account": "Contul meu",
        "nav.logout": "Logout",
        "search.placeholder": "Caută produse...",
        "contact.title": "Contact",
        "contact.subtitle": "Trimite-ne un mesaj și revenim cât mai rapid.",
        "contact.name": "Nume",
        "contact.email": "Email",
        "contact.message": "Mesaj",
        "contact.send": "Trimite mesaj"
    },
    en: {
        "logo.tagline": "Performance equipment",
        "nav.home": "Home",
        "nav.products": "Products",
        "nav.offers": "Offers",
        "nav.contact": "Contact",
        "nav.login": "Login",
        "nav.register": "Register",
        "nav.account": "My account",
        "nav.logout": "Logout",
        "search.placeholder": "Search products...",
        "contact.title": "Contact",
        "contact.subtitle": "Send us a message and we will reply soon.",
        "contact.name": "Name",
        "contact.email": "Email",
        "contact.message": "Message",
        "contact.send": "Send message"
    },
    ru: {
        "logo.tagline": "Спортивное оборудование",
        "nav.home": "Главная",
        "nav.products": "Товары",
        "nav.offers": "Акции",
        "nav.contact": "Контакты",
        "nav.login": "Вход",
        "nav.register": "Регистрация",
        "nav.account": "Мой аккаунт",
        "nav.logout": "Выход",
        "search.placeholder": "Поиск товаров...",
        "contact.title": "Контакты",
        "contact.subtitle": "Отправьте нам сообщение, и мы скоро ответим.",
        "contact.name": "Имя",
        "contact.email": "Email",
        "contact.message": "Сообщение",
        "contact.send": "Отправить"
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
        if (dict[key]) el.textContent = dict[key];
    });

    document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
        const key = el.dataset.i18nPlaceholder;
        if (dict[key]) el.placeholder = dict[key];
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
            card.style.display = text.includes(q) ? '' : 'none';
        });
    }

    input.addEventListener('input', filterProducts);

    if (input.value.trim() !== '') {
        filterProducts();
    }
}