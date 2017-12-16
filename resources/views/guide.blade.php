@extends('layouts.general')

@section('page-name', 'guide')

@section('content')


		


<main class="app-main">

		
        <section class="section-guide">


        	
   			<div class="container">
   				
   				<div class="section-guide__inner">
                <h1 class="section-guide__title-h1">How to upload live tours?</h1>
   				 <div class="section-guide__container">	
                    
                    @php($loopCount = 1)


                        @foreach($guideData as $data)
                            
            	   				<div class="section-guide__step">
            	   					<div class="section-guide__step-left">
            	   						<div class="section-guide__step-image"
                                             style="background-image: url({{ $data->image->url }});" 
                                        ></div>
                                        <div class="section-guide__step-heading">
                                            <h2 class="section-guide__image-title-h2"><span class="image-title__number">{{ $loopCount }}</span> {{ $data->title }}</h2>
                                        </div>
            	   					</div>
            	   					<div class="section-guide__step-right">
            	   						<p class="section-guide__image-title-p">
                                            {{ $data->description }}                  
                                        </p>
            	   					</div>
            	   				</div>
                                <hr class="section-faqs__item-divider">
                            
                        @php($loopCount++)

                    @endforeach

   			
                </div>

            </div>

        </div>
        	
        </section>





            

</main>

@endsection
