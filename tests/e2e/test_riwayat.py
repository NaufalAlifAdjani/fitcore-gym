import time
from playwright.sync_api import expect

# --- VERSI 3: VERSI TERBAIK (Auto-Waiting Playwright tanpa sleep) ---
def test_elemen_tiga_tombol_auto_waiting(page):
    page.goto("http://localhost:8000/login", wait_until="domcontentloaded")
    
    # BENAR: expect() otomatis menunggu ke-6 tombol selesai dimuat di DOM
    expect(page.locator("button[type='button'], button[type='submit']")).to_have_count(6)