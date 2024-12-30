{% extends "users/users-base.mvc" %}

{% block title %} Dashboard {% endblock %}
{% block body %}
<section class="py-5">
        <div class="container py-5">
            <div class="row d-flex justify-content-center">
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body text-center d-flex flex-column align-items-center">
                        <div class="card text-center row">
                            <div class="card-body">
                                <h5 class="card-title">{{$user->email}}</h5>
                            </div>
                        </div>
                        <form method="post" action="{{URL_ROOT}}/update/profile">
                                <div class="mb-3">
                                    <input class="form-control" type="email" name="email" value="{{$user->email}}" placeholder="Email" required disabled>
                                </div>
                                <div class="mb-3">
                                    <input class="form-control {% echo !empty($errors->email) ? 'is-invalid' : ''; %}" type="text" name="name" value="{{$user->name}}" placeholder="Name" required>
                                    <div class="invalid-feedback">{{$errors->name}}</div>
                                </div>
                                <?=  $CSRF ?>
                                <div class="mb-3">
                                    <button class="btn btn-primary shadow d-block w-100" type="submit">Update Profile</button>
                                </div>
                            </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
   
{% endblock %}

