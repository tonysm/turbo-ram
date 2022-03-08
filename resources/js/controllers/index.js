import HelloController from 'controllers/hello_controller';
import SearchController from 'controllers/search_controller';

const registerControllers = (Stimulus) => {
    Stimulus.register('hello', HelloController);
    Stimulus.register('search', SearchController);
};

export default registerControllers;
