@props(['active' => false])

<a  class="{{ $active  ?  ' bg-[#003C90] md:rounded-lg text-[#C8DFFF] font-bold' : 'text-[#C8DFFF] hover:text-blue-300 font-normal text-base hover:bg-[#5995ea]'  }} px-2 py-4 flex items-center rounded-md w-full text-base md:justify-center lg:justify-start gap-2" {{$attributes}}    aria-current="{{  $active ?  'page' : 'false'}}" >
    {{$slot}}
</a>
