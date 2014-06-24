/*! Ajax Search pro 2.0 js */
(function( $ ){                                     
  var $__instances = Array();   
  var methods = {
  
     getThis : function (x) {
       var id = $(x).attr('id').match(/^ajaxsearchpro(.*)/)[1];
       return $__instances[id];     
     },                                                                 
     
     init : function( options ) {      
       var id = $(this).attr('id').match(/^ajaxsearchpro(.*)/)[1];
       $__instances[id] = $.extend({},this, methods);
       
       $this = methods.getThis(this);
          
       $this.searching = false;
       $this.o = $.extend({}, options);
       $this.n = new Object();
       $this.n.container = $(this);
       $this.n.probox = $('.probox', this);
       $this.n.proinput = $('.proinput', this);
       $this.n.text = $('.proinput input.orig', this);
       $this.n.textAutocomplete = $('.proinput input.autocomplete', this);
       $this.n.loading = $('.proinput .loading', this);
       $this.n.proloading = $('.proloading', this);
       $this.n.promagnifier = $('.promagnifier', this);
       $this.n.prosettings = $('.prosettings', this);
       $this.n.searchsettings = $('.searchsettings', this);
       $this.n.resultsDiv = $(this).next();
       
       $this.n.showmore = $('.showmore a', $this.n.resultsDiv);
       $this.n.items = $('.item', $this.n.resultsDiv);
       $this.n.results =  $('.results', $this.n.resultsDiv);
       $this.n.resdrg =  $('.resdrg', $this.n.resultsDiv);
       $this.o.id = $this.n.container.attr('id').match(/^ajaxsearchpro(.*)/)[1];
       $this.firstClick = true;
       $this.post = null;
       $this.postAuto = null;
       $this.cleanUp();
       $this.n.text.val($this.o.defaultsearchtext); 
       $this.n.textAutocomplete.val('');  
       $this.o.resultitemheight = parseInt($this.o.resultitemheight);
       $this.scroll = new Object();
       $this.n.resultsAppend =  $('#wpdreams_asp_results_'+$this.o.id);
       
       $this.disableMobileScroll = false;      

       
       $this.n.searchsettings.appendTo("body");

       if ( $.browser.msie && $.browser.version<9 ) {
          $this.n.searchsettings.addClass("ie78");
       }

       if ($this.o.resultsposition=='hover') {
          $this.n.resultsDiv.appendTo("body");
       } else if ($this.n.resultsAppend.length > 0) {
          $this.n.resultsDiv.appendTo($this.n.resultsAppend);
       }

       if ($this.o.resultstype=='horizontal') {
          $this.createHorizontalScroll();
       } else if ($this.o.resultstype=='vertical') {
          $this.createVerticalScroll();
       }
       
       if ($this.o.resultstype=='polaroid')
        $this.n.results.addClass('photostack');

       $this.initEvents();
              
       return $this;
     },
     
     createVerticalScroll : function(  ) {
       var $this = methods.getThis(this);
       $this.scroll = $this.n.results.mCustomScrollbar({
          contentTouchScroll: true,
          scrollButtons:{ 
            enable:true, 
            scrollType:"pixels", 
            scrollSpeed: $this.o.resultitemheight, 
            scrollAmount: $this.o.resultitemheight
          },
          callbacks:{
            onScroll:function(){
                if (is_touch_device()) return;
                var top = parseInt($('.mCSB_container', $this.n.results).position().top);
                var children = $('.mCSB_container .resdrg').children();
                var overall = 0;
                var prev = 3000;
                var diff = 4000;
                var s_diff = 10000;
                var s_overall = 10000;
                var $last = null;
                children.each(function(){
                    diff = Math.abs((Math.abs(top)-overall));
                    if (diff<prev) {
                      s_diff = diff;
                      s_overall = overall;
                      $last = $(this);
                    } 
                    overall += $(this).outerHeight(true);
                    prev = diff;
                });
                if ($last.hasClass('group'))
                  s_overall = s_overall+($last.outerHeight(true)-$last.outerHeight(false));
                $(".mCSB_container", $this.n.resultsDiv).animate({
                  top: -s_overall
                }); 
            }
          }
       });
       
     },
     
     createHorizontalScroll : function(  ) {
       var $this = methods.getThis(this);
       
       $this.scroll = $this.n.results.mCustomScrollbar({
          horizontalScroll: true,
          contentTouchScroll: true,
          scrollButtons:{ 
            enable:true, 
            scrollType: 'pixels', 
            scrollSpeed: 'auto', 
            scrollAmount: 100
          }
       });
     },
     
     initEvents : function( ) {
       var $this = methods.getThis(this);
       $($this.n.text.parent()).submit(function(e){
          e.preventDefault();
          $this.n.text.keyup();
       });       
       $this.n.text.click(function(){
          if ($this.firstClick) {
            $(this).val('');
            $this.firstClick = false;
          }
       });
       $this.n.resultsDiv.css({
        opacity: 0
       });
       $(document).bind("click touchend", function(){
          if ($this.opened==false) return;
          if ($this.o.resultstype=='horizontal') {
            $this.hideHorizontalResults();
          } else {
            $this.hideVerticalResults();
          }
          $this.hideSettings();
       });
       $(this).bind("click touchend", function(e){
          e.stopImmediatePropagation();
       });
       $this.n.resultsDiv.bind("click touchend", function(e){
          e.stopImmediatePropagation();
       });
       $this.n.searchsettings.bind("click touchend", function(e){
          e.stopImmediatePropagation();
       });
       
       $this.n.prosettings.click(function(){
          if ($this.n.prosettings.attr('opened')==0) {
            $this.showSettings();
          } else {
            $this.hideSettings();          
          }
       });
       
       // jQuery bind not working!    
       $(document).bind('touchmove', function(e){
         if ($this.disableMobileScroll==true)  
           e.preventDefault();
       });
        
       $(window).bind("resize", function(){
           $this.resize();
       });
       $(window).bind("scroll", function() {
           $this.scrolling(false);
       });
       $(window).trigger('resize');
       $(window).trigger('scroll');
       
       $this.initMagnifierEvent();
       $this.initAutocompleteEvent();
       
     },
     
     initMagnifierEvent : function( ) {
       var $this = methods.getThis(this);
       
       var t; 
       $this.n.promagnifier.add($this.n.text).bind('click keyup', function(e){
         if (window.event) {
           $this.keycode = window.event.keyCode;
           $this.ktype = window.event.type;
         } else if (e) {
           $this.keycode = e.which;
           $this.ktype = e.type;
         }
         if ($(this).hasClass('orig') && $this.ktype=='click') return;
         if ($this.o.redirectonclick==1 && $this.ktype=='click') {
          location.href = $this.o.homeurl+'?s='+$this.n.text.val();
          return;
         }
         if ($this.o.triggeronclick==0 && $this.ktype=='click') return;

         if (($this.o.triggerontype==0 && $this.ktype=='keyup') || ($this.ktype=='keyup' && is_touch_device())) return;
         if ($this.post!=null) $this.post.abort();
         clearTimeout(t);         
         t = setTimeout (function() {
           $this.search();
         }, 400);
       });
     },

     initAutocompleteEvent : function( ) {
       var $this = methods.getThis(this);
       
       var tt;
       if ($this.o.autocomplete==1 && !is_touch_device()) {
         $this.n.text.keydown(function(e){
            if (window.event) {
              $this.keycode = window.event.keyCode;
              $this.ktype = window.event.type;
            } else if (e) {
              $this.keycode = e.which;
              $this.ktype = e.type;
            }
            if($this.keycode==39) {
              $this.n.text.val($this.n.textAutocomplete.val());
            } else {
                clearTimeout(tt);
                if ($this.postAuto!=null) $this.postAuto.abort();
                tt = setTimeout (function() {
                $this.autocomplete();
                tt = null;
               }, 30);
            }
         }); 
       }
     },
     
     destroy : function( ) {
       return this.each(function(){
         var $this =  $.extend({}, this, methods);
         $(window).unbind($this);
       })
     },
     searchfor: function(phrase) {
        $(".proinput input",this).val(phrase).trigger("keyup");
     },
     autocomplete : function() {
        var $this = methods.getThis(this);
        
        var val = $this.n.text.val();
        if ($this.n.text.val()=='') {
          $this.n.textAutocomplete.val('');
          return;
        }
        var autocompleteVal = $this.n.textAutocomplete.val();
        if (autocompleteVal!='' && autocompleteVal.indexOf(val)==0) {
          return;
        } else {
          $this.n.textAutocomplete.val('');
        }
        var data = {
      	  action: 'ajaxsearchpro_autocomplete',
          asid: $this.o.id,
          sauto: $this.n.text.val() 
      	};
      	$this.postAuto = $.post(ajaxsearchpro.ajaxurl, data, function(response) {
          if (response.length>0) {
            var part1 = val;
            var part2 = response.substr(val.length);
            response = part1 + part2;
          }
          $this.n.textAutocomplete.val(response);
        });
     },
     search : function() {
        var $this = methods.getThis(this);
        
        if ($this.searching && 0) return;
        if ($this.n.text.val().length<$this.o.charcount) return;
        $this.searching = true;
        $this.n.proloading.css({
           visibility: "visible"
        });
        $this.hideSettings();
        if ($this.o.resultstype=='horizontal') {
          $this.hideHorizontalResults(true);
        } else {
          $this.hideVerticalResults();
        }
        var data = {
      	  action: 'ajaxsearchpro_search',
          aspp: $this.n.text.val(),
          asid: $this.o.id,
          options: $('form' ,$this.n.searchsettings).serialize()  
      	};
      	$this.post = $.post(ajaxsearchpro.ajaxurl, data, function(response) {
          response = response.match(/!!ASPSTART!!(.*[\s\S]*)!!ASPEND!!/)[1];
          response = JSON.parse(response);
          $this.n.resdrg.html("");
          if (response.error!=null) {
            $this.n.resdrg.append("<div class='nores error'>"+response.error+"</div>");
          } else if (response.nores!=null && response.keywords!=null) {
            var str = $this.o.noresultstext+" "+$this.o.didyoumeantext+"<br>";
            for(var i=0;i<response.keywords.length;i++) {
              str = str + "<span class='keyword'>"+response.keywords[i]+"</span>";
            }
            $this.n.resdrg.append("<div class='nores'>"+str+"</div>");
            $(".keyword", $this.n.resdrg).bind('click', function(){
               $this.n.text.val($(this).html());
               $this.n.promagnifier.trigger('click');
            });
            if ($this.n.showmore!=null) {
              $this.n.showmore.css({
                 'display': 'none'
              });  
            }
          } else if (response.length>0 || response.grouped!=null) {
              if (response.grouped!=null && $this.o.resultstype=='vertical') {
                $.each(response.items, function(){
                    group = $this.createGroup(this.name);
                    group = $(group);
                    $this.n.resdrg.append(group);
                    $.each(this.data, function(){
                      result = $this.createResult(this);
                      result = $(result);
                      $this.n.resdrg.append(result);
                    });
                });
              } else {
                for(var i=0;i<response.length;i++) {
                    result = $this.createResult(response[i]);
                    result = $(result);
                    $this.n.resdrg.append(result);
                }
              }
              
              if ($this.n.showmore!=null) {
                $this.n.showmore.css({
                   'display': 'inline-block'
                });  
              }
          } else {
            $this.n.resdrg.append("<div class='nores'>"+$this.o.noresultstext+"</div>");
            if ($this.n.showmore!=null) {
              $this.n.showmore.css({
                 'display': 'none'
              });  
            }
          }
          $this.n.items = $('.item', $this.n.resultsDiv);
          
          if ($this.o.resultstype=='horizontal') {
            $this.showHorizontalResults();
          } else if ($this.o.resultstype=='vertical') {
            $this.showVerticalResults();
          } else if ($this.o.resultstype=='polaroid') {
            $this.showPolaroidResults();
            $this.disableMobileScroll = true; 
          }
   
          
          $this.n.proloading.css({
             visibility: "hidden"
          });
          if ($this.n.showmore!=null) {
             $this.n.showmore.attr('href', $this.o.homeurl+'?s='+$this.n.text.val());
          }  
      	}, "text"); 
     },

    /**
     * Chooses which creator to use: Vertical-Horizontal or Polaroid
     */    
     createResult : function(r) {
        var $this = methods.getThis(this);
        
        if ($this.o.resultstype=='horizontal' || $this.o.resultstype=='vertical')
          return $this.createVHResult(r);
        else 
          return $this.createPolaroidResult(r);
        
     },
     
     
    /**
     * Creates a result for Vertical and Horizontal types
     */                 
     createVHResult : function (r) {
        var $this = methods.getThis(this);
        
        var imageDiv = "";
        var desc = "";
        var authorSpan = "";
        var dateSpan = "";
        var isImage = false;
        if (r.image!=null && r.image!="") {
          imageDiv = "\
               <div class='image'>\
                <img src='"+r.image+"'> \
                <div class='void'></div>\
                </div>";
          isImage = true;
        }
        if ($this.o.showauthor==1) {
          authorSpan = "<span class='author'>"+r.author+"</span>"; 
        }
        if ($this.o.showdate==1) {
          dateSpan = "<span class='date'>"+r.date+"</span>";
        }
        if ($this.o.showdescription==1) {
          desc = r.content;
        }  
        if ($this.o.hresulthidedesc==1 && $this.o.resultstype=='horizontal' && isImage)              
          desc = '';
        var id = 'item_'+$this.o.id;
        var clickable = ""
        if ($this.o.resultareaclickable==1) {
          clickable = "<span class='overlap'></span>";
        }
        var result = "\
             <div class='item'> \
              "+imageDiv+" \
              <div class='content'> \
                <h3><a href='"+r.link+"'>"+r.title+clickable+"</a></h3>   \
                        <div class='etc'>"+authorSpan+" \
                        "+dateSpan+"</div> \
                <p class='desc'>"+desc+"</p>        \
              </div>     \
              <div class='clear'></div>   \
             </div>";
        return result;
     },
     
    /**
     * Creates a result for Polaroid types
     */        
                 
     createPolaroidResult : function (r) {
        var $this = methods.getThis(this);
       
        if (r.image!=null) {
           var image = "<img alt='img' src='" + r.image + "'>";
        } else {
           var image = r.content;
        }
        
        var clickable = "";
        var authorSpan = "";
        var dateSpan = "";
        var backDiv = "";
        if ($this.o.resultareaclickable==1) {
          clickable = "<span class='overlap'></span>";
        } 
        if ($this.o.pshowsubtitle==1) { 
          if ($this.o.showauthor==1) {
            authorSpan = "<span class='author'>"+r.author+"</span>"; 
          }
          if ($this.o.showdate==1) {
            dateSpan = "<span class='date'>"+r.date+"</span>";
          } 
        }
        if ($this.o.pshowdesc==1) {
          backDiv = "<div class='photostack-back'>" + r.content + "</div>";
        }
        var result = "\
          <figure class='photostack-flip photostack-current'> \
						<a class='photostack-img etc' href='"+r.link+"'>" + image + "</a> \
						<figcaption>  \
							<h2 class='photostack-title'><a href='"+r.link+"'>"+r.title+clickable+"</a></h2>  \
              <div class='etc'>"+authorSpan+" \
              "+dateSpan+"</div> " + backDiv + " \
						</figcaption>   \
					</figure>"; 
             
        return result;
     },
     
     createGroup: function(r) {
        return "<div class='group'>"+r+"</div>";
     },     
     
     showVerticalResults : function( ) {
        var $this = methods.getThis(this);
        $this.n.resultsDiv.css({
          display: 'block',
          height: 'auto' 
        });
        $this.scrolling(true);
        var top = $this.n.resultsDiv.position().top;
        $this.n.resultsDiv.css({
           "top": top-100,
           opacity: 0,
           visibility: "visible"
        }).animate({
           "top": top,
           opacity: 1
        },{
          complete: function() {
            $(".mCS_no_scrollbar", $this.n.resultsDiv).css({
              "top":0
            });
            $(".mCSB_container", $this.n.resultsDiv).css({
              "top":0
            });
          }
        });
        if ($this.n.items.length>0) {
          var count = (($this.n.items.length<$this.o.itemscount)?$this.n.items.length:$this.o.itemscount);
          var groups = $('.group', $this.n.resultsDiv);
          var i = 0;
          var h = 0;
          $this.n.items.each(function(){
              if (i<count) h += $(this).outerHeight(true);
              i++;                                                       
          });
          if (groups.length>0) {
             h += groups.outerHeight(true);
          }
          $this.n.results.css({
            height: h+3
          });
          $this.scroll.mCustomScrollbar('update');
          if ($this.o.highlight==1) {
            var wholew = (($this.o.highlightwholewords==1)?true:false);
            $("div.item", $this.n.resultsDiv).highlight($this.n.text.val().split(" "), { element: 'span', className: 'highlighted', wordsOnly: wholew });
          }
           
        } 
        $this.resize(); 
        if ($this.n.items.length==0) {
          var h = ($('.nores', $this.n.results).outerHeight(true)>($this.o.resultitemheight)?($this.o.resultitemheight):$('.nores', $this.n.results).outerHeight(true));
          $this.n.results.css({
            height: 11110
          }); 
          $this.scroll.mCustomScrollbar('update');
          $this.n.results.css({
            height: 'auto'
          }); 
        }
        $this.addAnimation();
        $this.scrolling(true);
        $this.searching = false;
        
     },
     
     showHorizontalResults : function( ) {
        var $this = methods.getThis(this); 
        
        $this.n.resultsDiv.css('display', 'block');

        if ($('.nores', $this.n.results).size()>0) {
          $(".mCSB_container", $this.n.resultsDiv).css({
             width: 'auto',
             left: 0
          });             
        } else {
          $(".mCSB_container", $this.n.resultsDiv).css({
             width: ($this.n.resdrg.children().size() * $($this.n.resdrg.children()[0]).outerWidth(true)),
             left: 0
          });             
        }
        if ($this.o.resultsposition=='hover')
          $this.n.resultsDiv.css('width', $this.n.container.outerWidth(true));
        $this.scroll.data({
          "scrollButtons_scrollAmount":parseInt($this.n.items.outerWidth(true)),
          "mouseWheelPixels":parseInt($this.n.items.outerWidth(true)) 
        }).mCustomScrollbar("update");
        
        if ($this.n.resultsDiv.css('visibility')=='visible') {
          var cssArgs = {
             height: 'auto'
          };
          var animArgs = {
             opacity: 1
          };  
        } else {
          var autoHeight = $this.n.resultsDiv.css('height', 'auto').height();
          
          var cssArgs = {
             opacity: 0,
             visibility: "visible",
             height: 0
          };
          
          var animArgs = {
             opacity: 1,
             height: autoHeight
          };  
        }
        
        $this.addAnimation();
               
        $this.n.resultsDiv.css(cssArgs).animate(
          animArgs
          ,{
          complete: function() {
            $this.scrolling(true);
            $this.searching = false;
          }
        });
     },
     
     showPolaroidResults : function( ) {
        var $this = methods.getThis(this);
        
        $('.photostack>nav', $this.n.resultsDiv).remove();
        var figures = $('figure', $this.n.resultsDiv);
        $this.n.resultsDiv.css({
          display: 'block',
          height: 'auto' 
        });
        $this.scrolling(true);
        var top = $this.n.resultsDiv.position().top;
        $this.n.resultsDiv.css({
           "top": top-100,
           opacity: 0,
           visibility: "visible"
        }).animate({
           "top": top,
           opacity: 1
        },{
          complete: function() {

          }
        });
        
        if (figures.length>0) {
          $this.n.results.css({
            height: $this.o.prescontainerheight
          });

          if ($this.o.highlight==1) {
            var wholew = (($this.o.highlightwholewords==1)?true:false);
            //$("div.item", $this.n.resultsDiv).highlight($this.n.text.val().split(" "), { element: 'span', className: 'highlighted', wordsOnly: wholew });
            //TODO
          }
          new Photostack( $this.n.results.get(0), {
          	callback : function( item ) {
          	}
          }); 
        } 
        //$this.resize(); 
        if (figures.length==0) {
          var h = ($('.nores', $this.n.results).outerHeight(true)>($this.o.resultitemheight)?($this.o.resultitemheight):$('.nores', $this.n.results).outerHeight(true));
          $this.n.results.css({
            height: 11110
          }); 
          $this.n.results.css({
            height: "auto"
          }); 
        } 
        $this.addAnimation();
        $this.scrolling(true);
        $this.searching = false;
        $this.initPolaroidEvents(figures);


     },
     
     initPolaroidEvents : function(figures) {
        var $this = methods.getThis(this);
        
        var i = 1;
        figures.each(function(){
          if (i>1)
           $(this).removeClass('photostack-current');
          $(this).attr('idx', i);
          i++;
        });

        figures.click(function(e){
            if ($(this).hasClass("photostack-current")) return;
            e.preventDefault();
            var idx = $(this).attr('idx');
            $('.photostack>nav span:nth-child('+idx+')', $this.n.resultsDiv).click();
        });
        
        figures.bind('mousewheel', function(event, delta) {
            event.preventDefault();
            if (delta >= 1) {
                if ($('.photostack>nav span.current', $this.n.resultsDiv).next().length > 0) {
                   $('.photostack>nav span.current', $this.n.resultsDiv).next().click();
                } else {
                   $('.photostack>nav span:nth-child(1)', $this.n.resultsDiv).click();
                }
            } else {
                if ($('.photostack>nav span.current', $this.n.resultsDiv).prev().length > 0) {
                   $('.photostack>nav span.current', $this.n.resultsDiv).prev().click();
                } else {
                   $('.photostack>nav span:nth-last-child(1)', $this.n.resultsDiv).click();
                }
            }
        });
            
        figures.bind("swipeone",function(e, originalEvent){
          e.preventDefault();
          e.stopPropagation();
          originalEvent.originalEvent.preventDefault();
          originalEvent.originalEvent.stopPropagation()
          if (originalEvent.delta!=null && originalEvent.delta[0]!=null && originalEvent.delta[0].lastX!=null) {
            if(originalEvent.delta[0].lastX>=0) {
                  if ($('.photostack>nav span.current', $this.n.resultsDiv).next().length > 0) {
                     $('.photostack>nav span.current', $this.n.resultsDiv).next().click();
                  } else {
                     $('.photostack>nav span:nth-child(1)', $this.n.resultsDiv).click();
                  }
            } else {
                  if ($('.photostack>nav span.current', $this.n.resultsDiv).prev().length > 0) {
                     $('.photostack>nav span.current', $this.n.resultsDiv).prev().click();
                  } else {
                     $('.photostack>nav span:nth-last-child(1)', $this.n.resultsDiv).click();
                  }
            }
          }
        });
        $this.disableMobileScroll = true;
     },
     
     hideVerticalResults : function() {
        var $this = methods.getThis(this);
        
        $this.disableMobileScroll = false;
        
        $this.n.resultsDiv
        .animate({
           opacity: 0,
           height: 0
        }, {
          duration: 120,
          complete: function() {
            $(this).css({
              visibility: "hidden",
              display: "none"
            });
          }
        });
     },
     
     hideHorizontalResults : function( newSearch ) {
        //var $this =  $.extend({}, this, methods);
        var $this = methods.getThis(this);
        
        $this.disableMobileScroll = false;
        
        newSearch = typeof newSearch !== 'undefined' ? newSearch : false;
        if (!newSearch) {
          $this.n.resultsDiv
          .animate({
             opacity: 0,
             height: 0
          },{
            duration: 120,
            complete: function() {
              $(this).css({
                visibility: "hidden",
                display: "none"
              });
            }
          });        
        } else {
          $this.n.resultsDiv
          .animate({
             opacity: 0.3
          },{
            complete: function() {
              return;
            }
          });        
        }
     },  
     
     addAnimation : function ( ) {
      var $this = methods.getThis(this);

      var animation = (($this.o.resultstype=="vertical")?$this.o.vresultanimation:$this.o.hresultanimation);
      var i = 0;
      $this.n.items.each(function(){
        var x = this;
        setTimeout(function() {
          $(x).addClass(animation);
        }, i);
        i = i + 60;
      });      
     },   
     
     showSettings : function( ) {
       var $this = methods.getThis(this);
       
       $this.scrolling(true);
       $this.n.searchsettings.css({
        opacity: 0,
        visibility: "visible",
        top: "-=50px"
       });
       $this.n.searchsettings.animate({
        opacity: 1,
        top: "+=50px"
       });
       $this.n.prosettings.attr('opened', 1);       
     },   
     hideSettings : function( ) {
       var $this = methods.getThis(this);
       
       $this.n.searchsettings.animate({
        opacity: 0
       }, {
        complete: function() {
          $(this).css({
            visibility: "hidden"
          });
        }
       });
       $this.n.prosettings.attr('opened', 0);
     },
     cleanUp: function( ) {
        var $this = methods.getThis(this);
        
        if ($('.searchsettings', $this.n.container).length>0) {
               $('body>#ajaxsearchprosettings'+$this.o.id).remove();
               $('body>#ajaxsearchprores'+$this.o.id).remove();
        }
     },  
     resize : function( ) {
       var $this = methods.getThis(this); 
        
       $this.n.proinput.css({
          width: ($this.n.probox.width()-8-($this.n.proinput.outerWidth(false)-$this.n.proinput.width())-$this.n.proloading.outerWidth(true)-$this.n.prosettings.outerWidth(true)-$this.n.promagnifier.outerWidth(true)-10)
       });
       $this.n.text.css({
          width: $this.n.proinput.width()-2+$this.n.proloading.outerWidth(true),
          position: 'absolute',
          zIndex: 2
       });
       $this.n.textAutocomplete.css({
          width: $this.n.proinput.width()-2+$this.n.proloading.outerWidth(true),
          position: 'absolute',
          top: $this.n.text.position().top,
          left: $this.n.text.position().left, 
          opacity: 0.25,
          zIndex: 1
       });
       
       if ($this.n.prosettings.attr('opened')!=0) {
    
         if ($this.o.settingsimagepos=='left') {
           $this.n.searchsettings.css({
             display: "block",
             top: $this.n.prosettings.offset().top+$this.n.prosettings.height()-2,
             left: $this.n.prosettings.offset().left 
           });         
         } else {
           $this.n.searchsettings.css({
             display: "block",
             top: $this.n.prosettings.offset().top+$this.n.prosettings.height()-2,
             left: $this.n.prosettings.offset().left+$this.n.prosettings.width()-$this.n.searchsettings.width() 
           });
         }
       }
       if ($this.n.resultsDiv.css('visibility')!='hidden') {
       
         if ($this.o.resultsposition!='block') {
           $this.n.resultsDiv.css({
              width: $this.n.container.width()-($this.n.resultsDiv.outerWidth(true)-$this.n.resultsDiv.width()),
              top: $this.n.container.offset().top+$this.n.container.outerHeight(true)+10,
              left: $this.n.container.offset().left
           }); 
         }
         
         $('.content', $this.n.items).each(function(){
            var imageWidth = (($(this).prev().css('display')=="none")?0:$(this).prev().outerWidth(true));
            $(this).css({
             width: ($(this.parentNode).width()-$(this).prev().outerWidth(true)-$(this).outerWidth(false) + $(this).width()) - 3
            }); 
         });
         
        }   
     },
     scrolling: function(ignoreVisibility) {
       var $this = methods.getThis(this);
       
       if (ignoreVisibility==true || $this.n.searchsettings.css('visibility')=='visible') {

         if ($this.o.settingsimagepos=='left') {
           $this.n.searchsettings.css({
             display: "block",
             top: $this.n.prosettings.offset().top+$this.n.prosettings.height()-2,
             left: $this.n.prosettings.offset().left
           });         
         } else {
           $this.n.searchsettings.css({
             display: "block",
             top: $this.n.prosettings.offset().top+$this.n.prosettings.height()-2,
             left: $this.n.prosettings.offset().left+$this.n.prosettings.width()-$this.n.searchsettings.width() 
           });
         }
       }

       if ((ignoreVisibility==true || $this.n.resultsDiv.css('visibility')=='visible')) {
         var cwidth = $this.n.container.outerWidth(true);
         if ($this.o.resultsposition!='hover' && $this.n.resultsAppend.length > 0) 
            cwidth = 'auto';
         else 
            cwidth = cwidth-(2*parseInt($this.n.resultsDiv.css('paddingLeft')));
         $this.n.resultsDiv.css({
            width: cwidth,
            top: $this.n.container.offset().top+$this.n.container.outerHeight(true)+10,
            left: $this.n.container.offset().left
         });
         if ($this.o.resultstype!='vertical') return; 
         $('.content', $this.n.items).each(function(){
            $(this).css({
             width: ($(this.parentNode).width()-$(this).prev().outerWidth(true)-$(this).outerWidth(false) + $(this).width()) - 3
            });
            /*$(this).css({
             width: ($(this.parentNode).width()-$(this).prev().outerWidth(true))
            });*/ 
         });
       }
     }
  };

  $.fn.ajaxsearchpro = function( method ) {
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.ajaxsearchpro' );
    }    
  
  };
	function is_touch_device(){
		return !!("ontouchstart" in window) ? 1 : 0;
	}
})( aspjQuery );
