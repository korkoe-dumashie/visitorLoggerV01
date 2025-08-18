@props(['active' => false])

<a  class="{{ $active  ?  ' bg-[#003C90] md:rounded-lg text-[#C8DFFF] font-bold' : 'text-[#C8DFFF] hover:text-blue-300 font-normal lg:text-base hover:bg-[#5995ea] md:text-lg'  }} lg:px-5 flex items-center rounded-md w-full lg:py-4 px-4 py-3 md:text-lg md:justify-center lg:justify-start gap-2" {{$attributes}}    aria-current="{{  $active ?  'page' : 'false'}}" >
      {{$slot}}              
 </a>
 