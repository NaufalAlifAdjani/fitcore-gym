from playwright.sync_api import expect

# 1. Menguji Tombol Menggunakan Role dan Nama (Prioritas Utama)
def test_tombol_login_dengan_role(page):
    page.goto("http://localhost:8000/login", wait_until="domcontentloaded")

    tombol = page.get_by_role("button", name="Masuk Sekarang")
    expect(tombol).to_be_visible()


# 2. Menguji Input Menggunakan Label Semantik
def test_input_dengan_label(page):
    page.goto("http://localhost:8000/login", wait_until="domcontentloaded")

    input_email = page.get_by_label("Email atau Nomor WhatsApp")
    expect(input_email).to_be_visible()


# 3. Menguji Tombol Tab Role Menggunakan Role Semantik
def test_tab_trainer_dengan_role(page):
    page.goto("http://localhost:8000/login", wait_until="domcontentloaded")
    
    tab_trainer = page.get_by_role("button", name="Personal Trainer")
    expect(tab_trainer).to_be_visible()