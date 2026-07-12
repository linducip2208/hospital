@if(!empty($faqs))
    <h2 class="h4 fw-bold mt-5 mb-3">Pertanyaan yang Sering Diajukan</h2>
    <div class="accordion" id="faqAccordion">
        @foreach($faqs as $i => $f)
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button {{ $i === 0 ? '' : 'collapsed' }}" type="button"
                        data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}">
                        {{ $f['q'] }}
                    </button>
                </h3>
                <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted">{{ $f['a'] }}</div>
                </div>
            </div>
        @endforeach
    </div>
@endif
