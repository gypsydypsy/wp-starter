import SliderSingle from "./blocks/bloc_slider_single_type3";
import SliderMulti from "./blocks/bloc_slider_multi";
import Video from "./blocks/bloc_video";
import Accordion from "./blocks/bloc_accordeon";
import Form from "./components/Form";
import Select from "./components/Select";


const main = {
    init: function() {
        SliderSingle.init();
        SliderMulti.init();
        Video.init();
        Accordion.init();
        Select.init();
        Form.init();
    }
};

export default main