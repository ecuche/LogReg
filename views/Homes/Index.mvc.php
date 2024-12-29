{% extends "base.mvc" %}

{% block title %} Welcome | LogIn {% endblock %}
{% block body %}
<section class="py-5">
        <div class="container py-5">
            <div class="row d-flex justify-content-center">
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body text-center d-flex flex-column align-items-center">
                            <div class="bs-icon-xl bs-icon-circle bs-icon-primary shadow bs-icon my-4">
                                <i class="bi-person-fill"></i>
                            </div>
                            <form method="post" action="{{URL_ROOT}}/log-in-user">
                                {% if(!empty($success)): %}
                                <div class="alert alert-success" role="alert">
                                    {{$success}}
                                </div>
                                {% endif; %}
                                {% if(!empty($warn)): %}
                                <div class="alert alert-warning" role="alert">
                                    {{$warn}}
                                </div>
                                {% endif; %}
                                {% if(!empty($auth)): %}
                                <div class="alert alert-warning" role="alert">
                                    {{$auth}}
                                </div>
                                {% endif; %}
                                {% if(!empty($errors->login)): %}
                                <div class="alert alert-danger" role="alert">
                                    {{$errors->login}}
                                </div>
                                {% endif; %} 

                                <div class="mb-3">
                                    <input class="form-control {% echo !empty($errors->email) || !empty($errors->login) ? 'is-invalid' : ''; %}" type="email" name="email" value="{{$user->email}}" placeholder="Email" required>
                                    <div class="invalid-feedback">{{$errors->email}}</div>
                                </div>
                                <div class="mb-3">
                                    <div class="input-group">
                                        <input class="form-control {% echo !empty($errors->password) || !empty($errors->login) ? 'is-invalid' : ''; %}" type="password" name="password" id="password" placeholder="Password" data-bs-theme="dark" minlength="6" aria-describedby="passwordHelpBlock" required>
                                        <i class="input-group-text bi-eye-slash-fill hide-show-password"></i>
                                    </div>
                                </div>
                                <div class="mb-3 form-check form-switch form-check-reverse">
                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="remember_me" {% echo !empty($user->remember_me) ? 'checked' : ''; %}>
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Remember Me</label>
                                </div>
                                <?=  $CSRF ?>
                                <div class="mb-3"><button class="btn btn-primary shadow d-block w-100" type="submit">Log in</button></div><p class="text-muted">Have Account? <a href="{{ URL_ROOT }}/register">Register</a></p><p class="text-muted"><a href="{{URL_ROOT}}/forgot-password">Forgot your password?</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
   
{% endblock %}

