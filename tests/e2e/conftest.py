import pytest

# Helper API Simulasi Backend FitCore
class FitCoreAPIHelper:
    def __init__(self, base_url="http://localhost:8000"):
        self.base_url = base_url
        self.database_simulasi = {}

    def post(self, endpoint, payload):
        # Menyiapkan data sesi PT FitCore secara instan
        sesi_id = payload.get("id", "SESI-PT-003")
        self.database_simulasi[sesi_id] = payload
        return {"status": 201, "data": payload}

    def delete(self, endpoint):
        # Membersihkan data setelah pengetesan (teardown)
        sesi_id = endpoint.split("/")[-1]
        self.database_simulasi.pop(sesi_id, None)
        return {"status": 200, "message": "deleted"}

    def get(self, endpoint):
        sesi_id = endpoint.split("/")[-1]
        return self.database_simulasi.get(sesi_id, None)


@pytest.fixture
def api():
    return FitCoreAPIHelper()


@pytest.fixture
def sesi_pt_processing(api):
    # Setup Precondition lewat API langsung (Hanya butuh 0.01 detik)
    sesi = api.post("/api/test/sesi-pt", {
        "id": "SESI-PT-003",
        "member_id": "MEM-001",
        "trainer_id": "PT-002",
        "status": "PROCESSING",
    })

    yield sesi  # Titik eksekusi pengujian berjalan di sini

    # Teardown / Pembersihan otomatis setelah tes selesai
    api.delete("/api/test/sesi-pt/SESI-PT-003")