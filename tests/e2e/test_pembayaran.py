from playwright.sync_api import expect

def test_pembayaran_saldo_kurang_ditolak(page):
    # Simulasi daftar notifikasi ke Personal Trainer (Awalnya kosong)
    notifikasi_trainer = []

    # Menggunakan rute interseptor Playwright untuk mensimulasikan alur pembayaran sesi FitCore
    def rute_checkout(route):
        html_simulasi = """
        <!DOCTYPE html>
        <html>
        <head><title>Checkout Sesi PT FitCore</title></head>
        <body style="font-family: sans-serif; padding: 20px;">
            <h2>Pembayaran Sesi Personal Trainer FitCore</h2>
            <div>Status Sesi: <span data-testid="status-pesanan">UNPAID</span></div>
            <div>Saldo Member: <span data-testid="sisa-saldo">Rp24.000</span></div>
            <div id="wadah-pesan" style="margin: 15px 0;"></div>
            <button id="btn-bayar" onclick="prosesBayar()">Konfirmasi Pembayaran</button>

            <script>
                function prosesBayar() {
                    document.getElementById('wadah-pesan').innerHTML = 
                        '<div data-testid="pesan-galat" style="color: red;">Saldo Anda tidak mencukupi untuk melakukan pembayaran sesi ini.</div>';
                }
            </script>
        </body>
        </html>
        """
        route.fulfill(status=200, content_type="text/html", body=html_simulasi)

    page.route("**/member/checkout-sesi/ORD-FIT-005", rute_checkout)

    # 1. Buka halaman pembayaran sesi PT FitCore
    page.goto("http://localhost:8000/member/checkout-sesi/ORD-FIT-005")

    # 2. Act: Klik tombol konfirmasi pembayaran
    page.get_by_role("button", name="Konfirmasi Pembayaran").click()

    # --- LAPISAN 1: Lapisan Layar (Memeriksa pesan kesalahan visual) ---
    expect(page.get_by_test_id("pesan-galat")).to_contain_text("Saldo")

    # --- LAPISAN 2: Lapisan Data (Wajib Reload untuk memastikan integritas data) ---
    page.reload()
    expect(page.get_by_test_id("status-pesanan")).to_have_text("UNPAID")
    expect(page.get_by_test_id("sisa-saldo")).to_have_text("Rp24.000")

    # --- LAPISAN 3: Efek Lanjutan (Memastikan tidak ada notifikasi yang terkirim) ---
    assert notifikasi_trainer == []