<?php if (session()->get('user_level') == 3) : ?>
        <li class="nav-item <?= url_is('/pengaduan') || url_is('/pengaduan/tambah') ? 'active' : '' ?>">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsTwo" aria-expanded="true" aria-controls="collapsTwo">
                <?php if (url_is('/pengaduan/tambah')) : ?>
                    <i class="fas fa-plus"></i>
                <?php else : ?>
                    <i class="fas fa-list"></i>
                <?php endif; ?>
                <span>Pengaduan Saya</span>
            </a>
            <ul class="list-unstyled ps-0">
            <li class="mb-1">
        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
          Main Menu
        </button>
        <div class="collapse show" id="home-collapse" style="">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Pengajuan Surat</a></li>
            <li><a href="/pengaduan" class="link-dark d-inline-flex text-decoration-none rounded">Pengaduan</a></li>
            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Reports</a></li>
          </ul>
        </div>
      </li>
    <?php endif; ?>

    <ul class="list-unstyled ps-0">
      <li class="mb-1">
        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
          Home
        </button>
        <div class="collapse show" id="home-collapse" style="">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Overview</a></li>
            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Updates</a></li>
            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Reports</a></li>
          </ul>
        </div>
      </li>
      <li class="mb-1">
        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="true">
          Dashboard
        </button>
        <div class="collapse show" id="dashboard-collapse" style="">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Overview</a></li>
            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Weekly</a></li>
            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Monthly</a></li>
            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Annually</a></li>
          </ul>
        </div>
      </li>
      <li class="mb-1">
        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
          Orders
        </button>
        <div class="collapse" id="orders-collapse">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">New</a></li>
            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Processed</a></li>
            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Shipped</a></li>
            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Returned</a></li>
          </ul>
        </div>
      </li>
      <li class="border-top my-3"></li>
      <li class="mb-1">
        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
          Account
        </button>
        <div class="collapse" id="account-collapse" style="">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">New...</a></li>
            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Profile</a></li>
            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Settings</a></li>
            <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Sign out</a></li>
          </ul>
        </div>
      </li>
    </ul>