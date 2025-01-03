<nav class="navbar navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
  {% if(isset($_SESSION['id'])): %}
  <a class="navbar-brand d-flex align-items-center" href="{{ URL_ROOT.'/dashboard' }}">
    {% else :%}
    <a class="navbar-brand d-flex align-items-center" href="{{ URL_ROOT }}">
    {% endif; %}
    <span class="bs-icon-sm bs-icon-circle bs-icon-primary shadow d-flex justify-content-center align-items-center me-2 bs-icon">
        <i class="bi bi-grid-fill"></i>
    </span>
    <span>{{SITE_NAME}}</span>
  </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
      <div class="offcanvas-header">
      {% if(isset($_SESSION['id'])): %}
        <a class="nav-link" aria-current="page" href="{{URL_ROOT.'/profile/view'}}">
          <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">{% echo ucwords($_SESSION["name"]) %}</h5>
        </a>
      {% endif; %}  
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="{{URL_ROOT.'/contact'}}">Contact Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="{{URL_ROOT.'/about-us'}}">About</a>
          </li>
          {% if(isset($_SESSION['id'])): %}
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="{{URL_ROOT.'/Logout'}}">Logout</a>
          </li>
        {% endif; %}  
        </ul>
      </div>
    </div>
  </div>
</nav>
