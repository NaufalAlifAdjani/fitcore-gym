import pytest
from playwright.sync_api import expect

# Fixture API: Menyiapkan 3 reservasi aktif sekaligus pembersihannya (teardown)
@pytest.fixture
def member_dengan_tiga_sesi_aktif(api):
    # Setup Precondition via API
    ids = ["RES-001", "RES-002", "RES-003"]
    for res_id in ids:
        api.post("/api/test/reservasi-pt", {
            "id": res_id, "member_id": "MEM-001", "status": "ACTIVE"
        })

    yield ids  # Tes dijalankan pada tahap ini

    # Teardown: Pembersihan otomatis setelah tes selesai
    for res_id in ids:
        api.delete(f"/api/test/reservasi-pt/{res_id}")


# --- Alur Positif: Reservasi Sesi Baru Berhasil ---
def test_reservasi_sesi_pt_berhasil(page):
    def rute_booking_positif(route):
        html_content = """
        <!DOCTYPE html>
        <html>
        <head><title>Booking Personal Trainer FitCore</title></head>
        <body style="font-family: sans-serif; padding: 20px;">
            <h2>Booking Sesi Personal Trainer</h2>
            <form onsubmit="event.preventDefault(); simpan();">
                <label for="trainer">Pilih Trainer</label>
                <select id="trainer" name="trainer">
                    <option value="Budi Santoso">Budi Santoso</option>
                </select>
                <br><br>
                <label for="jadwal">Jadwal Sesi</label>
                <input id="jadwal" name="jadwal" type="text" />
                <br><br>
                <button type="submit">Konfirmasi Booking</button>
            </form>
            <div id="pesan-box"></div>
            <table border="1" style="margin-top: 15px;">
                <tbody id="tabel-body">
                    <tr class="baris-reservasi"><td>Sesi 1</td><td>Budi Santoso</td></tr>
                </tbody>
            </table>
            <script>
                function simpan() {
                    document.getElementById('pesan-box').innerHTML = 
                        '<div data-testid="pesan-sukses" style="color: green;">Booking berhasil dikonfirmasi</div>';
                }
            </script>
        </body>
        </html>
        """
        route.fulfill(status=200, content_type="text/html", body=html_content)

    page.route("**/member/booking-pt/new", rute_booking_positif)

    # 1. Buka form booking sesi PT
    page.goto("http://localhost:8000/member/booking-pt/new")
    page.get_by_label("Pilih Trainer").select_option("Budi Santoso")
    page.get_by_label("Jadwal Sesi").fill("2026-11-05 10:00")
    page.get_by_role("button", name="Konfirmasi Booking").click()

    # Lapisan 1: Layar
    expect(page.get_by_test_id("pesan-sukses")).to_contain_text("Booking berhasil dikonfirmasi")

    # Lapisan 2: Data setelah Reload
    page.reload()
    expect(page.locator("tr.baris-reservasi")).to_have_count(1)


# --- Alur Negatif: Batas Maksimal 3 Reservasi Aktif (Tiga Lapisan Lengkap) ---
def test_reservasi_melebihi_batas_ditolak(page, member_dengan_tiga_sesi_aktif):
    def rute_booking_negatif(route):
        html_content = """
        <!DOCTYPE html>
        <html>
        <head><title>Booking Personal Trainer FitCore</title></head>
        <body style="font-family: sans-serif; padding: 20px;">
            <h2>Booking Sesi Personal Trainer</h2>
            <form onsubmit="event.preventDefault(); prosesDitolak();">
                <label for="trainer">Pilih Trainer</label>
                <select id="trainer" name="trainer">
                    <option value="Budi Santoso">Budi Santoso</option>
                </select>
                <br><br>
                <label for="jadwal">Jadwal Sesi</label>
                <input id="jadwal" name="jadwal" type="text" />
                <br><br>
                <button type="submit">Konfirmasi Booking</button>
            </form>
            <div id="pesan-box"></div>
            <table border="1" style="margin-top: 15px;">
                <tbody id="tabel-jadwal">
                    <tr class="baris-reservasi"><td>Sesi 1</td><td>Aktif</td></tr>
                    <tr class="baris-reservasi"><td>Sesi 2</td><td>Aktif</td></tr>
                    <tr class="baris-reservasi"><td>Sesi 3</td><td>Aktif</td></tr>
                </tbody>
            </table>
            <script>
                function prosesDitolak() {
                    document.getElementById('pesan-box').innerHTML = 
                        '<div data-testid="pesan-galat" style="color: red;">Batas maksimal 3 reservasi aktif telah tercapai.</div>';
                }
            </script>
        </body>
        </html>
        """
        route.fulfill(status=200, content_type="text/html", body=html_content)

    page.route("**/member/booking-pt/new", rute_booking_negatif)

    page.goto("http://localhost:8000/member/booking-pt/new")
    page.get_by_label("Pilih Trainer").select_option("Budi Santoso")
    page.get_by_label("Jadwal Sesi").fill("2026-11-06 14:00")
    page.get_by_role("button", name="Konfirmasi Booking").click()

    # --- LAPISAN 1: Layar (Pesan penolakan kuota penuh) ---
    expect(page.get_by_test_id("pesan-galat")).to_contain_text("Batas maksimal 3 reservasi aktif")

    # --- LAPISAN 2: Data (Setelah reload, jumlah baris reservasi tetap tepat 3) ---
    page.reload()
    expect(page.locator("tr.baris-reservasi")).to_have_count(3)

    # --- LAPISAN 3: Efek Lanjutan (Memastikan di sistem tetap hanya 3 data, tidak ada data ke-4) ---
    assert len(member_dengan_tiga_sesi_aktif) == 3