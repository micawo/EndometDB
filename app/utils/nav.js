import $ from 'jquery';
import clickEvent from '../utils/clickevent';

export default function initNav(elem) {

    if(document.querySelector("#user_menu") !== null) {
        
        $("#user_menu_link").on(clickEvent, function(e) {
            
            e.preventDefault();
            $("#user_menu").toggleClass("show");    
        });
    }
}
