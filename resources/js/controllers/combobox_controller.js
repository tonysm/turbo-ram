import { Controller } from '@hotwired/stimulus';
import Combobox from '@github/combobox-nav';

export default class extends Controller {
    static targets = ['input', 'listbox'];

    connect() {
        this.combobox = new Combobox(this.inputTarget, this.listboxTarget);
    }

    start() {
        this.combobox.start();
    }

    stop() {
        this.combobox.stop();
    }
}
