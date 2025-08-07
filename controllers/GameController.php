<?php

class GameController extends Controller {
  
    public function index() {
        // Check localStorage first for quick access
        echo '<script>
            if (!localStorage.getItem("user")) {
                window.location.href = "/zombie";
            }
        </script>';
        echo '<script>
            localStorage.setItem("musicState", "off");
        </script>';
        // Initialize userInfo from server
        
        
        $this->render('game');
    }
} 