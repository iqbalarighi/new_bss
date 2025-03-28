
<script>
        jQuery(document).ready(function($){
            $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
            });
        })
        </script>
<style>
            body {
        overflow-x: hidden;
        }
        #sidebar-wrapper {
        min-height: 100vh;
        margin-left: -15rem;
        -webkit-transition: margin .25s ease-out;
        -moz-transition: margin .25s ease-out;
        -o-transition: margin .25s ease-out;
        transition: margin .25s ease-out;
        }
        #sidebar-wrapper .sidebar-heading {
        padding: 0.875rem 1.25rem;
        font-size: 1.2rem;
        }
        #sidebar-wrapper .list-group {
        width: 15rem;
        }
        #page-content-wrapper {
        min-width: 100vw;
        }
        #wrapper.toggled #sidebar-wrapper {
        margin-left: 0;
        }
        @media (min-width: 768px) {
        #sidebar-wrapper {
            margin-left: 0;
        }
        #page-content-wrapper {
            min-width: 0;
            width: 100%;
        }
        #wrapper.toggled #sidebar-wrapper {
            margin-left: -15rem;
        }
        }

        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1;
        }
        .footer {
            background: #333;
            color: white;
            text-align: center;
            position: sticky;
            bottom: 0;
            width: 100%;
        }

        a {text-decoration: none;}

        

    .list-group-item.active {
    z-index: 2;
    color: #000000;
    background-color: #ffffff;
    border-color: #ffffff;
    font-weight: bolder;
}
    th, td {
        vertical-align: middle !important;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        width: 100%;
/*        max-width: 600px;*/
    }
</style>