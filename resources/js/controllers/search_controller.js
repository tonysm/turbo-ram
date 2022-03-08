import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static targets = ['results', 'input'];

    openWhenFocused() {
        this.resultsTarget.removeAttribute('hidden');
    }

    closeWhenEmpty() {
        if (this.inputTarget.value) return;

        this.resultsTarget.setAttribute('hidden', true);
    }
}
