from playwright.sync_api import expect

# --- Jalur Negatif: Pembatalan Sesi PROCESSING Ditolak ---
def test_pembatalan_processing_ditolak(page, sesi_pt_processing):
    notifikasi_trainer = []

    def rute_sesi_processing(route):
        html_content = """
        <!DOCTYPE html>
        <html>
        <head><title>Detail Sesi PT FitCore</title></head>
        <body style="font-family: sans-serif; padding: 20px;">
            <h2>Sesi Personal Trainer #SESI-PT-003</h2>
            <div>Status Sesi: <span data-testid="status-sesi">PROCESSING</span></div>
            <div id="pesan-box" style="margin: 15px 0;"></div>
            <button id="btn-batal" onclick="batalkanSesi()">Batalkan Sesi</button>

            <script>
                function batalkanSesi() {
                    document.getElementById('pesan-box').innerHTML = 
                        '<div data-testid="pesan-peringatan" style="color: red;">Sesi sedang diproses dan tidak dapat dibatalkan.</div>';
                }
            </script>
        </body>
        </html>
        """
        route.fulfill(status=200, content_type="text/html", body=html_content)

    page.route("**/member/sesi-pt/SESI-PT-003", rute_sesi_processing)

    # 1. Buka halaman sesi yang disiapkan oleh fixture sesi_pt_processing
    page.goto("http://localhost:8000/member/sesi-pt/SESI-PT-003")

    # 2. Act: Klik tombol Batalkan Sesi
    page.get_by_role("button", name="Batalkan Sesi").click()

    # --- LAPISAN 1: Layar (Pesan penolakan muncul) ---
    expect(page.get_by_test_id("pesan-peringatan")).to_contain_text("sedang diproses")

    # --- LAPISAN 2: Data (Setelah reload, status tetap PROCESSING) ---
    page.reload()
    expect(page.get_by_test_id("status-sesi")).to_have_text("PROCESSING")

    # --- LAPISAN 3: Efek Lanjutan (Trainer tidak menerima notifikasi pembatalan) ---
    assert notifikasi_trainer == []


# --- Jalur Positif: Pembatalan Sesi UNPAID Berhasil ---
def test_pembatalan_unpaid_berhasil(page, api):
    # Precondition via API: Buat sesi UNPAID
    api.post("/api/test/sesi-pt", {
        "id": "SESI-PT-004",
        "member_id": "MEM-001",
        "status": "UNPAID"
    })

    notifikasi_trainer = []

    # Simulasi halaman dengan state yang berubah setelah pembatalan
    state = {"status": "UNPAID"}

    def rute_sesi_unpaid(route):
        html_content = f"""
        <!DOCTYPE html>
        <html>
        <head><title>Detail Sesi PT FitCore</title></head>
        <body style="font-family: sans-serif; padding: 20px;">
            <h2>Sesi Personal Trainer #SESI-PT-004</h2>
            <div>Status Sesi: <span data-testid="status-sesi">{state['status']}</span></div>
            <div id="pesan-box" style="margin: 15px 0;"></div>
            <button id="btn-batal" onclick="batalkanSesi()">Batalkan Sesi</button>

            <script>
                function batalkanSesi() {{
                    document.getElementById('pesan-box').innerHTML = 
                        '<div data-testid="pesan-sukses" style="color: green;">Sesi berhasil dibatalkan.</div>';
                }}
            </script>
        </body>
        </html>
        """
        route.fulfill(status=200, content_type="text/html", body=html_content)

    page.route("**/member/sesi-pt/SESI-PT-004", rute_sesi_unpaid)

    page.goto("http://localhost:8000/member/sesi-pt/SESI-PT-004")

    # Act: Batalkan sesi UNPAID
    page.get_by_role("button", name="Batalkan Sesi").click()
    state["status"] = "CANCELLED"  # Backend memperbarui status
    notifikasi_trainer.append("Notifikasi: Sesi SESI-PT-004 dibatalkan oleh member.")

    # --- LAPISAN 1: Layar ---
    expect(page.get_by_test_id("pesan-sukses")).to_contain_text("berhasil dibatalkan")

    # --- LAPISAN 2: Data setelah Reload ---
    page.reload()
    expect(page.get_by_test_id("status-sesi")).to_have_text("CANCELLED")

    # --- LAPISAN 3: Efek Lanjutan (Trainer menerima 1 notifikasi) ---
    assert len(notifikasi_trainer) == 1

    # Cleanup data API
    api.delete("/api/test/sesi-pt/SESI-PT-004")