{% extends "base.mvc" %}
{% block title %} Forgot Password {% endblock %}
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
                            <form method="post" action="{{URL_ROOT}}/recover-account">
                                <div class="mb-3">
                                    <input class="form-control {% echo !empty($errors->email) ? 'is-invalid' : ''; %}" type="email" name="email" placeholder="Email" value="{{$user->email}}" required>
                                    <div class="invalid-feedback">{{$errors->email}}</div>
                                </div>
                                {% echo $CSRF %}
                                <div class="mb-3">
                                    <button class="btn btn-primary shadow d-block w-100" type="submit">Recover Password</button>
                                </div>
                                <p class="text-muted">
                                    Don't have Account? <a href="{{ URL_ROOT }}/register">Register</a>
                                </p>
                                <div class="mb-3">

                                </div>
                                <p class="text-muted">Already have an account? 
                                    <a href="{{URL_ROOT}}">Log in</a>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
   
{% endblock %}

