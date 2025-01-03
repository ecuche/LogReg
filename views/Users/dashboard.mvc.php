{% extends "base.mvc" %}

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
                            

{{$user->email}}
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
   
{% endblock %}

