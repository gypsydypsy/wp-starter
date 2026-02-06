import Header from "./components/Header";
import SliderSingle from "./blocks/slider_single";
import SliderMulti from "./blocks/slider_multi";
import Video from "./blocks/video";
import Accordion from "./blocks/accordion";
import Form from "./components/Form";
import Select from "./components/Select";
import Modal from "./components/Modal";
import Accessibility from "./utils/accessibility";


const main = {
    init: function() {
        Header.init();
        SliderSingle.init();
        SliderMulti.init();
        Video.init();
        Accordion.init();
        Select.init();
        Form.init();
        Modal.init();
        Accessibility.init();
    }
};

export default main