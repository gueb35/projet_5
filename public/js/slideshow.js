var Slideshow = (function(){
    var self = {},
        config = {
            duration:4,
            container:{
                width:0,
                height:0
            }
        };
        
    setOptions = function (){
        if(typeof options === 'object' && Object.keys(options).length >0){
            for (var property in options){
                if (config[property] !== undefined)
                {
                    config[property] = options[property];
                }
            }
        }
    }

    self.init = function(opts) 
    {
        options = opts || {};
        setOptions();
        execute();
    }

    execute = function(){
        container = document.querySelector('#slideshow-container');
        slideshow = document.querySelector('#slideshow');
        images = Array.from(document.querySelectorAll('.slideshow-image'));
        if((config.container.width && config.container.height) > 0){
            setContainerStyle();
        }
        i =-1;
        play();
    }

    setContainerStyle = function(){
        container.style.position = 'relative';
        container.style.width = config.container.width + 'px';
        container.style.height = config.container.height + 'px';
    }

    play = function(){
        slideshow.classList.add('fadeOut');

        setTimeout(function(){
            slideshow.style.background = 'url(' + images[i].src + ')center';
            slideshow.style.backgroundSize = 'cover';
            slideshow.classList.remove('fadeOut');
        },1000);
        i++;
        if(i == images.length){
            i = 0;
        }
        setTimeout(play, config.duration * 1000);
        
    }
    
    return self;
})();