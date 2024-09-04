<style>
    @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap');
</style>

@props(['route' => '#', 'buttonText' => 'Button', 'primaryColor' => '#2563EB', 'secondaryColor' => '#1D4ED8'])

<a href="{{ $route }}" 
   style="
       display: inline-block;
       background-color: {{ $primaryColor }};
       color: white;
       font-size: 18px;
       font-weight: bold;
       padding: 10px 20px;
       border-radius: 8px;
       cursor: pointer;
       transition: background-color 0.3s;
       font-family: 'Bebas Neue', sans-serif;
       letter-spacing: 1px;
   "
   onmouseover="this.style.backgroundColor='{{ $secondaryColor }}'"
   onmouseout="this.style.backgroundColor='{{ $primaryColor }}'">
    {{ $buttonText }}
</a>
