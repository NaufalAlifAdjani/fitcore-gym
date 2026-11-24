from playwright.sync_api import expect

def test_login_member_berhasil(page):
    # 1. Arrange: Buka halaman login FitCore
    page.goto("http://localhost:8000/login", wait_until="domcontentloaded")

    # 2. Act: Masukkan kredensial member terdaftar
    page.get_by_label("Email atau Nomor WhatsApp").fill("member@fitcore.test")
    page.get_by_label("Kata Sandi").fill("password123")
    page.get_by_role("button", name="Masuk Sekarang").click()

    # 3. Assert: Berhasil diarahkan ke dashboard member
    expect(page).to_have_url("http://localhost:8000/member/dashboard")
    expect(page.get_by_text("Konten Beranda Member")).to_be_visible()


def test_login_kata_sandi_salah(page):
    # 1. Arrange: Buka halaman login
    page.goto("http://localhost:8000/login", wait_until="domcontentloaded")

    # 2. Act: Masukkan kata sandi salah
    page.get_by_label("Email atau Nomor WhatsApp").fill("member@fitcore.test")
    page.get_by_label("Kata Sandi").fill("PasswordSalahTotal")
    page.get_by_role("button", name="Masuk Sekarang").click()

    page.wait_for_load_state("domcontentloaded")

    # 3. Assert: Pengguna tetap di halaman login (akses ditolak)
    expect(page).to_have_url("http://localhost:8000/login")