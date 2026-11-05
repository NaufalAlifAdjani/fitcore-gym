from playwright.sync_api import expect

# --- Versi 1: Precondition Lewat API Fixture (Sangat Cepat & Mandiri) ---
def test_precondition_lewat_api(sesi_pt_processing):
    # Data sudah langsung siap status PROCESSING tanpa perlu mengeklik form
    assert sesi_pt_processing["data"]["status"] == "PROCESSING"
    assert sesi_pt_processing["data"]["id"] == "SESI-PT-003"


# --- Versi 2: Precondition Lewat Antarmuka (Klik Manual dari Login - Lambat) ---
def test_precondition_lewat_antarmuka_klik(page):
    # Harus membuka halaman login, mengisi form, dan klik tombol satu per satu
    page.goto("http://localhost:8000/login", wait_until="domcontentloaded")
    page.get_by_label("Email atau Nomor WhatsApp").fill("member@fitcore.test")
    page.get_by_label("Kata Sandi").fill("password123")
    page.get_by_role("button", name="Masuk Sekarang").click()

    expect(page).to_have_url("http://localhost:8000/member/dashboard")