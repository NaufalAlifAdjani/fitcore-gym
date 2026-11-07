from playwright.sync_api import expect

def test_login_member_berhasil(page):
    # Arrange — Buka halaman login FitCore
    page.goto("http://localhost:8000/login", wait_until="domcontentloaded")

    # Act — Isi formulir login member
    page.get_by_label("Email atau Nomor WhatsApp").fill("member@fitcore.test")
    page.get_by_label("Kata Sandi").fill("password123")
    page.get_by_role("button", name="Masuk Sekarang").click()

    # Assert — Berhasil masuk ke dashboard member
    expect(page).to_have_url("http://localhost:8000/member/dashboard")
    expect(page.get_by_text("Konten Beranda Member")).to_be_visible()


# --- Jalur Negatif: Kata Sandi Salah ---
def test_login_kata_sandi_salah(page):
    page.goto("http://localhost:8000/login", wait_until="domcontentloaded")

    # Act — Masukkan kata sandi salah
    page.get_by_label("Email atau Nomor WhatsApp").fill("member@fitcore.test")
    page.get_by_label("Kata Sandi").fill("PasswordSalahTotal")
    page.get_by_role("button", name="Masuk Sekarang").click()

    page.wait_for_load_state("domcontentloaded")

    # Assert — Tetap di halaman login dan pilih elemen pertama yang menampilkan pesan galat
    expect(page).to_have_url("http://localhost:8000/login")
    expect(page.get_by_text("Teks Sengaja Salah Agar Gagal").first).to_be_visible(timeout=3000)