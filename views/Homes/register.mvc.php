{% extends "base.mvc" %}

{% block title %} Register {% endblock %}

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
                            <form method="post" action="{{URL_ROOT}}/register-new-user">
                                <div class="mb-3"></div><div class="mb-3">
                                    <input class="form-control  {% echo !empty($errors->name) ? 'is-invalid' : ''; %}" type="text" name="name" value="{{$user->name}}" placeholder="Full Name"  required/>
                                    <div class="invalid-feedback">{{$errors->name}}</div>
                                    
                                </div>
                                <div class="mb-3">
                                    <input class="form-control {% echo !empty($errors->email) ? 'is-invalid' : ''; %}" type="email" name="email" value="{{$user->email}}" placeholder="email"  required/>
                                    <div class="invalid-feedback"> {{$errors->email}} </div>
                                </div>
                                <div class="mb-3">
                                    <div class="input-group">
                                        <input class="form-control {% echo !empty($errors->password) ? 'is-invalid' : ''; %}" id="password" type="password" name="password" placeholder="Password" data-bs-theme="dark" minlength="6" aria-describedby="passwordHelpBlock" required />
                                        <i class="input-group-text bi-eye-slash-fill hide-show-password"></i>
                                    </div>
                                    <div id="passwordHelpBlock" class="form-text {% echo !empty($errors->password) ? 'text-danger' : ''; %}">
                                        Your password must be atleast 6 characters long, contain UPPER and lower case letters and numbers.
                                    </div>
                                </div>
                                {% echo $CSRF %}
                                <button class="btn btn-primary shadow d-block w-100" type="submit">Sign up</button>
                                <div class="mb-3"></div><p class="text-muted">Already have an account? <a href="{{URL_ROOT}}">Log in</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
   
{% endblock %}

