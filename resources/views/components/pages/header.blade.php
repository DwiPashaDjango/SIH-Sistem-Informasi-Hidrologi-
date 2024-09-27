<div class="container-fluid py-3">
    <div class="d-flex justify-content-between">
        <div>
            <img src="{{asset('logo.png')}}" alt="" class="img-logo">
        </div>
        <div>
            <div style="margin-right: 20px; margin-top: 8px">
                <strong id="clock">
                    
                </strong>
            </div>
        </div>
    </div>
</div>

<script>
    function updateClock() {
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        const now = new Date();
        const day = days[now.getDay()];
        const date = now.getDate();
        const month = months[now.getMonth()];
        const year = now.getFullYear();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        const timeString = `${day}, ${date} ${month} ${year} - ${hours}:${minutes}:${seconds}`;
        document.getElementById('clock').textContent = timeString;
    }

    setInterval(updateClock, 1000);
    updateClock();
</script>
