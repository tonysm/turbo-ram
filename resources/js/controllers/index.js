import HelloController from 'controllers/hello_controller';

const registerControllers = (Stimulus) => {
    Stimulus.register('hello', HelloController);
};

export default registerControllers;
