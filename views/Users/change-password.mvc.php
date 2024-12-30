{% extends "users/users-base.mvc" %}

{% block title %} Dashboard {% endblock %}
{% block body %}
<section class="py-5">
        <div class="container py-5">
            <div class="row d-flex justify-content-center">
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body text-center d-flex flex-column align-items-center">
                        {% if(!empty($success)): %}
                            <div class="alert alert-success" role="alert">
                                {{$success}}
                            </div>
                        {% endif; %}
                        <div class="card text-center row">
                            <div class="card-body">
                                <h5 class="card-title">{{$user->email}}</h5>
                                <a href="{{$_ENV['URL_ROOT']}}/profile/update" class="btn btn-primary mb-3" class="btn btn-primary">Edit Profile</a>
                                <a href="{{$_ENV['URL_ROOT']}}/password/change" class="btn btn-primary mb-3" class="btn btn-primary">Change Password</a>
                            </div>
                        </div>
                        <form method="post" action="{{URL_ROOT}}/log-in-user">
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
                                <?=  $CSRF ?>
                                <div class="mb-3"><button class="btn btn-primary shadow d-block w-100" type="submit">Log in</button></div><p class="text-muted">Have Account? <a href="{{ URL_ROOT }}/register">Register</a></p><p class="text-muted"><a href="{{URL_ROOT}}/forgot-password">Forgot your password?</a></p>
                            </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
   
{% endblock %}

