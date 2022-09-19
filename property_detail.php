 <?php
    require "includes/database_connect.php";

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;
    $property_id = $_GET['property_id'];

    $sql1 = "SELECT * FROM properties WHERE id=$property_id";
    $result1 = mysqli_query($conn, $sql1);
    if (!$result1) {
        echo "Something went wrong :(";
        return;
    }

    $property = mysqli_fetch_assoc($result1);
    if (!$property) {
        echo "Something went wrong!";
    }

    $sql2 = "SELECT * FROM interested_user_properties WHERE property_id=$property_id AND user_id=1";

    $result2 = mysqli_query($conn, $sql2);

    if (!$result2) {
        echo "Something went wrong :(";
    }

    $iup = mysqli_fetch_assoc($result2);

    $is_interested = false;
    if ($iup) {
        $is_interested = true;
    }

    $sql3 = "SELECT COUNT(*) AS count FROM interested_user_properties WHERE property_id=$property_id";
    $result3 = mysqli_query($conn, $sql3);

    if (!$result3) {
        echo "Something went wrong :(";
    }

    $total_interested = mysqli_fetch_assoc($result3);

    $sql4 = "SELECT * 
                FROM properties_ameneties pa 
                INNER JOIN amenities a ON pa.amenity_id = a.id
                WHERE pa.property_id = $property_id";

    $result4 = mysqli_query($conn, $sql4);
    if (!$result4) {
        echo "Something went wrong :(";
    }

    $amenities = mysqli_fetch_all($result4, MYSQLI_ASSOC);
    $amenities_building = [];
    $amenities_common_area = [];
    $amenities_bedroom = [];
    $amenities_washroom = [];

    foreach ($amenities as $amenity) {
        if ($amenity['type'] == "Common Area") {
            $amenities_common_area[$amenity['name']] = $amenity['icon'];
        } elseif ($amenity['type'] == "Building") {
            $amenities_building[$amenity['name']] = $amenity['icon'];
        } elseif ($amenity['type'] == "Bedroom") {
            $amenities_bedroom[$amenity['name']] = $amenity['icon'];
        } elseif ($amenity['type'] == "Washroom") {
            $amenities_washroom[$amenity['name']] = $amenity['icon'];
        }
    }

    $sql5 = "SELECT content, full_name  
                FROM testimonials t 
                INNER JOIN users u 
                ON t.user_id = u.id
                WHERE property_id=$property_id";

    $result5 = mysqli_query($conn, $sql5);

    if (!$result5) {
        echo "Something Went worng :(";
    }

    $testimonials = mysqli_fetch_all($result5, MYSQLI_ASSOC);

    ?>
 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title><?= $property['name'] ?> | PG Life</title>

     <link href="css/bootstrap.min.css" rel="stylesheet" />
     <link href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" rel="stylesheet" />
     <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet" />
     <link href="css/common.css" rel="stylesheet" />
     <link href="css/property_detail.css" rel="stylesheet" />
 </head>

 <body>
     <?php
        include "includes/header.php"
        ?>

     <div id="loading">
     </div>

     <nav aria-label="breadcrumb">
         <ol class="breadcrumb py-2">
             <li class="breadcrumb-item">
                 <a href="index.php">Home</a>
             </li>
             <li class="breadcrumb-item">
                 <a href="property_list.php">Mumbai</a>
             </li>
             <li class="breadcrumb-item active" aria-current="page">
                 <?= $property['name'] ?>
             </li>
         </ol>
     </nav>

     <div id="property-images" class="carousel slide" data-ride="carousel">
         <ol class="carousel-indicators">
             <li data-target="#property-images" data-slide-to="0" class="active"></li>
             <li data-target="#property-images" data-slide-to="1" class=""></li>
             <li data-target="#property-images" data-slide-to="2" class=""></li>
         </ol>
         <div class="carousel-inner">
             <div class="carousel-item active">
                 <img class="d-block w-100" src="img/properties/1/1d4f0757fdb86d5f.jpg" alt="slide">
             </div>
             <div class="carousel-item">
                 <img class="d-block w-100" src="img/properties/1/46ebbb537aa9fb0a.jpg" alt="slide">
             </div>
             <div class="carousel-item">
                 <img class="d-block w-100" src="img/properties/1/eace7b9114fd6046.jpg" alt="slide">
             </div>
         </div>
         <a class="carousel-control-prev" href="#property-images" role="button" data-slide="prev">
             <span class="carousel-control-prev-icon" aria-hidden="true"></span>
             <span class="sr-only">Previous</span>
         </a>
         <a class="carousel-control-next" href="#property-images" role="button" data-slide="next">
             <span class="carousel-control-next-icon" aria-hidden="true"></span>
             <span class="sr-only">Next</span>
         </a>
     </div>

     <div class="property-summary page-container">
         <div class="row no-gutters justify-content-between">
             <?php
                $total_rating = ($property['rating_clean'] + $property['rating_food'] + $property['rating_safety']) / 3;
                $total_rating = round($total_rating, 1);
                ?>
             <div class="star-container" title="4.5">
                 <?php
                    $rating = $total_rating;
                    for ($i = 0; $i < 5; $i++) {
                        if ($rating >= $i + 0.8) {
                    ?>
                         <i class="fas fa-star"></i>
                     <?php
                        } elseif ($rating >= $i + 0.3) {
                        ?>
                         <i class="fas fa-star-half-alt"></i>
                     <?php
                        } else {
                        ?>
                         <i class="far fa-star"></i>
                 <?php
                        }
                    }
                    ?>
             </div>
             <div class="interested-container">
                 <?php
                    if ($is_interested) {
                    ?>
                     <i class="is-interested-image fas fa-heart"></i>
                 <?php
                    } else {
                    ?>
                     <i class="is-interested-image far fa-heart"></i>
                 <?php
                    }
                    ?>
                 <div class="interested-text">
                     <span class="interested-user-count"><?= $total_interested['count'] ?></span> interested
                 </div>
             </div>
         </div>
         <div class="detail-container">
             <div class="property-name"><?= $property['name'] ?></div>
             <div class="property-address"><?= $property['address'] ?></div>
             <div class="property-gender">
                 <?php
                    if ($property['gender'] == "male") {
                    ?>
                     <img src="img/male.png" />
                 <?php
                    } elseif ($property['gender'] == "female") {
                    ?>
                     <img src="img/female.png" />
                 <?php
                    } else {
                    ?>
                     <img src="img/unisex.png" />
                 <?php } ?>
             </div>
         </div>
         <div class="row no-gutters">
             <div class="rent-container col-6">
                 <div class="rent">Rs. <?= $property['rent'] ?>/-</div>
                 <div class="rent-unit">per month</div>
             </div>
             <div class="button-container col-6">
                 <a href="#" class="btn btn-primary">Book Now</a>
             </div>
         </div>
     </div>

     <div class="property-amenities">
         <div class="page-container">
             <h1>Amenities</h1>
             <div class="row justify-content-between">
                 <div class="col-md-auto">
                     <h5>Building</h5>
                     <?php
                        foreach ($amenities_building as $name => $icon) {
                        ?>
                         <div class="amenity-container">
                             <img src="img/amenities/<?= $icon ?>.svg">
                             <span><?= $name ?></span>
                         </div>

                     <?php } ?>
                 </div>

                 <div class="col-md-auto">
                     <h5>Common Area</h5>
                     <?php
                        foreach ($amenities_common_area as $name => $icon) {
                        ?>
                         <div class="amenity-container">
                             <img src="img/amenities/<?= $icon ?>.svg">
                             <span><?= $name ?></span>
                         </div>
                     <?php } ?>
                 </div>

                 <div class="col-md-auto">
                     <h5>Bedroom</h5>
                     <?php
                        foreach ($amenities_bedroom as $name => $icon) {
                        ?>
                         <div class="amenity-container">
                             <img src="img/amenities/<?= $icon ?>.svg">
                             <span><?= $name ?></span>
                         </div>
                     <?php } ?>
                 </div>

                 <div class="col-md-auto">
                     <h5>Washroom</h5>
                     <?php
                        foreach ($amenities_washroom as $name => $icon) {
                        ?>
                         <div class="amenity-container">
                             <img src="img/amenities/<?= $icon ?>.svg">
                             <span><?= $name ?></span>
                         </div>
                     <?php } ?>
                 </div>
             </div>
         </div>
     </div>

     <div class="property-about page-container">
         <h1>About the Property</h1>
         <p><?= $property['description'] ?></p>
     </div>

     <div class="property-rating">
         <div class="page-container">
             <h1>Property Rating</h1>
             <div class="row align-items-center justify-content-between">
                 <div class="col-md-6">
                     <div class="rating-criteria row">
                         <div class="col-6">
                             <i class="rating-criteria-icon fas fa-broom"></i>
                             <span class="rating-criteria-text">Cleanliness</span>
                         </div>
                         <div class="rating-criteria-star-container col-6" title="4.3">
                             <?php
                                $rating_clean = $property['rating_clean'];
                                for ($i = 0; $i < 5; $i++) {
                                    if ($rating_clean >= $i + 0.8) {
                                ?>
                                     <i class="fas fa-star"></i>
                                 <?php
                                    } elseif ($rating_clean >= $i + 0.3) {
                                    ?>
                                     <i class="fas fa-star-half-alt"></i>
                                 <?php
                                    } else {
                                    ?>
                                     <i class="far fa-star"></i>
                             <?php
                                    }
                                }
                                ?>
                         </div>
                     </div>

                     <div class="rating-criteria row">
                         <div class="col-6">
                             <i class="rating-criteria-icon fas fa-utensils"></i>
                             <span class="rating-criteria-text">Food Quality</span>
                         </div>
                         <div class="rating-criteria-star-container col-6" title="3.4">
                             <?php
                                $rating_food = $property['rating_food'];
                                for ($i = 0; $i < 5; $i++) {
                                    if ($rating_food >= $i + 0.8) {
                                ?>
                                     <i class="fas fa-star"></i>
                                 <?php
                                    } elseif ($rating_food >= $i + 0.3) {
                                    ?>
                                     <i class="fas fa-star-half-alt"></i>
                                 <?php
                                    } else {
                                    ?>
                                     <i class="far fa-star"></i>
                             <?php
                                    }
                                }
                                ?>
                         </div>
                     </div>

                     <div class="rating-criteria row">
                         <div class="col-6">
                             <i class="rating-criteria-icon fa fa-lock"></i>
                             <span class="rating-criteria-text">Safety</span>
                         </div>
                         <div class="rating-criteria-star-container col-6" title="4.8">
                             <?php
                                $rating_safety = $property['rating_safety'];
                                for ($i = 0; $i < 5; $i++) {
                                    if ($rating_food >= $i + 0.8) {
                                ?>
                                     <i class="fas fa-star"></i>
                                 <?php
                                    } elseif ($rating_safety >= $i + 0.3) {
                                    ?>
                                     <i class="fas fa-star-half-alt"></i>
                                 <?php
                                    } else {
                                    ?>
                                     <i class="far fa-star"></i>
                             <?php
                                    }
                                }
                                ?>
                         </div>
                     </div>
                 </div>

                 <div class="col-md-4">
                     <div class="rating-circle">
                         <div class="total-rating">4.2</div>
                         <div class="rating-circle-star-container">
                             <i class="fas fa-star"></i>
                             <i class="fas fa-star"></i>
                             <i class="fas fa-star"></i>
                             <i class="fas fa-star"></i>
                             <i class="far fa-star"></i>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>

     <div class="property-testimonials page-container">
         <h1>What people say</h1>
         <?php
            foreach ($testimonials as $testimonial) {
            ?>
             <div class="testimonial-block">
                 <div class="testimonial-image-container">
                     <img class="testimonial-img" src="img/man.png">
                 </div>
                 <div class="testimonial-text">
                     <i class="fa fa-quote-left" aria-hidden="true"></i>
                     <p><?= $testimonial['content'] ?></p>
                 </div>
                 <div class="testimonial-name"><?= $testimonial['full_name'] ?></div>
             </div>
         <?php } ?>
     </div>

     <?php
        include "includes/footer.php"
        ?>

     <script type="text/javascript" src="js/property_detail.js"></script>
 </body>

 </html>