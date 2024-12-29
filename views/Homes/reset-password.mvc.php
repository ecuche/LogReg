{% extends "base.mvc" %}

{% block title %} Reset Password {% endblock %}
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
                            <form method="post" action="{{URL_ROOT}}/password/reset/<?= "{$user->email}/$hash->hash" ?>">  
                                <div class="mb-3 row content-center">
                                    <div class="card text-center">
                                        <div class="card-header">
                                            Password Reset
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{$user->email}}</h5>
                                        </div>
                                        <div class="card-footer text-muted">
                                            {{$user->name}}
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 input-group">
                                    <input class="form-control {% echo !empty($errors->password) ? 'is-invalid' : ''; %}" type="password" name="password" id="password" placeholder="Password" value="" data-bs-theme="dark" minlength="6" aria-describedby="passwordHelpBlock" >
                                    <i class="input-group-text bi-eye-slash-fill hide-show-password"></i>
                                </div>
                                <div class="mb-3 text-danger" id="passwordHelpBlock">{{$errors->password}}</div>
                               
                                <div class="mb-3">
                                    <div class="input-group">
                                        <input class="form-control {% echo !empty($errors->password) ? 'is-invalid' : ''; %}" type="password" name="password_again" id="password" placeholder="Confirm Password"  data-bs-theme="dark" minlength="6" aria-describedby="passwordHelpBlock">
                                        <i class="input-group-text bi-eye-slash-fill hide-show-password"></i>
                                    </div>
                                    <div id="passwordHelpBlock" class="form-text {% echo !empty($errors->password) ? 'text-danger' : ''; %}">
                                        Your password must be atleast 6 characters long, contain UPPER and lower case letters and numbers.
                                    </div>
                                </div>
                                {% echo $CSRF %}

                                <div class="mb-3"><button class="btn btn-primary shadow d-block w-100" type="submit">Reset Password</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
   
{% endblock %}

