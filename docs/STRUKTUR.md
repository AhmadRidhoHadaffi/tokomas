# Struktur Aplikasi

Alur: Route -> Filter (csrf, auth, guest, role, throttle) -> Controller -> Service -> Model/Entity -> View.

- app/Controllers/Public, Owner, Staff, Customer: controller tipis.
- app/Controllers/Ajax: JSON same-origin untuk interaksi ringan.
- app/Views/layouts: layout CI4 dengan extend, section, renderSection.
- app/Views/components, partials, app/Cells: presentasi reusable.
- resources/css/app.css: input Tailwind; output public/assets/css/app.css.
- app/Services: aturan bisnis bersama controller web dan Ajax.
- app/Database/Migrations: sumber kebenaran skema, tidak diubah.

Autentikasi memakai session. JWT, CORS lintas origin, React, dan Blade tidak dipakai.
