import HelloController from 'controllers/hello_controller';
import SearchController from 'controllers/search_controller';
import ComboboxController from 'controllers/combobox_controller';

const registerControllers = (Stimulus) => {
    Stimulus.register('hello', HelloController);
    Stimulus.register('search', SearchController);
    Stimulus.register('combobox', ComboboxController);
};

export default registerControllers;
