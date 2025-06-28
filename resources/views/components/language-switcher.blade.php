<!-- مبدل اللغة -->
<div class="dropdown">
    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-globe me-2"></i>
        @switch(app()->getLocale())
            @case('ar')
                <span class="me-1">🇮🇶</span> العربية
                @break
            @case('en')
                <span class="me-1">🇺🇸</span> English
                @break
            @case('ku')
                <span class="me-1">🟨</span> کوردی
                @break
            @default
                <span class="me-1">🇮🇶</span> العربية
        @endswitch
    </button>
    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
        <!-- العربية -->
        <li>
            <a class="dropdown-item {{ app()->getLocale() === 'ar' ? 'active' : '' }}" 
               href="{{ route('language.switch', 'ar') }}">
                <span class="me-2">🇮🇶</span>
                العربية
                @if(app()->getLocale() === 'ar')
                    <i class="fas fa-check text-success ms-auto"></i>
                @endif
            </a>
        </li>
        
        <!-- الإنجليزية -->
        <li>
            <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}" 
               href="{{ route('language.switch', 'en') }}">
                <span class="me-2">🇺🇸</span>
                English
                @if(app()->getLocale() === 'en')
                    <i class="fas fa-check text-success ms-auto"></i>
                @endif
            </a>
        </li>
        
        <!-- الكردية -->
        <li>
            <a class="dropdown-item {{ app()->getLocale() === 'ku' ? 'active' : '' }}" 
               href="{{ route('language.switch', 'ku') }}">
                <span class="me-2">🟨</span>
                کوردی
                @if(app()->getLocale() === 'ku')
                    <i class="fas fa-check text-success ms-auto"></i>
                @endif
            </a>
        </li>
    </ul>
</div>

<style>
.dropdown-item.active {
    background-color: #e3f2fd;
    color: #1976d2;
}

.dropdown-item:hover {
    background-color: #f5f5f5;
}

.dropdown-menu {
    min-width: 150px;
}

/* تحسين الاتجاه للغات RTL */
@if(in_array(app()->getLocale(), ['ar', 'ku']))
.dropdown-menu {
    text-align: right;
}
@endif
</style>
