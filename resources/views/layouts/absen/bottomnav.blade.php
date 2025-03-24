 <!-- App Bottom Menu -->
    <div class="appBottomMenu">
        <a href="{{route('absen')}}" class="item {{Request::is('absen') ? 'active' : ''}}">
            <div class="col">
                <ion-icon name="home-outline" role="img" class="md hydrated"
                    aria-label="file tray full outline"></ion-icon>
                <strong>Home</strong>
            </div>
        </a>
        <a href="{{route('absen.histori')}}" class="item {{Request::is('absen/histori') ? 'active' : ''}}">
            <div class="col">
                <ion-icon name="document-text-outline" role="img" class="md hydrated"
                    aria-label="document text outline"></ion-icon>
                <strong>History</strong>
            </div>
        </a>
        <a href="/absen/create" class="item">
            <div class="col">
                <div class="action-button large">
                    <ion-icon name="camera" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
                </div>
            </div>
        </a>
        <a href="#" class="item">
            <div class="col">
                <ion-icon name="document-text-outline" role="img" class="md hydrated"
                    aria-label="document text outline"></ion-icon>
                <strong>Izin</strong>
            </div>
        </a>
        <a href="/absen/profile" class="item {{Request::is('absen/profile') ? 'active' : ''}}">
            <div class="col">
                <ion-icon name="people-outline" role="img" class="md hydrated" aria-label="people outline"></ion-icon>
                <strong>Profile</strong>
            </div>
        </a>
    </div>
    <!-- * App Bottom Menu -->