import { Application } from '@hotwired/stimulus'
import registerControllers from 'controllers';

window.Stimulus = Application.start();

registerControllers(Stimulus);

export default { Stimulus: window.Stimulus };
