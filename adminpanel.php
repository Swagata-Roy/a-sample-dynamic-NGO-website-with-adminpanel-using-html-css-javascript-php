<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Example NGO Admin Panel</title>
    <link rel="stylesheet" href="css/adminpanel.css">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
</head>
<body>
<div class="sidebar">
    <h1>Example NGO</h1>
    <ul>
        <li><a href="#" onclick="showSection('manageUsers')">Manage Users</a></li>
        <li><a href="#" onclick="showSection('homePage')">Home Page</a></li>
        <li><a href="#" onclick="showSection('aboutUs')">About Us</a></li>
        <li><a href="#" onclick="showSection('projects')">Programs/Projects</a></li>
        <li><a href="#" onclick="showSection('photos')">Photos</a></li>
        <li><a href="#" onclick="showSection('videos')">Videos</a></li>
        <li><a href="#" onclick="showSection('contactUs')">Contact Us</a></li>
        <li><a href="#" onclick="showSection('volunteer')">Volunteer</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="header">
        <h2 id="sectionTitle">Dashboard</h2>
        <div class="user-dropdown">
            <button onclick="toggleDropdown()" class="dropbtn"><?php echo htmlspecialchars($_SESSION['username']); ?> ▼</button>
            <div id="myDropdown" class="dropdown-content">
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
    <!--Manage Users-->
    <div id="manageUsers" class="content-section">
        <h2>Manage Users</h2>

        <div class="button-group">
            <button onclick="toggleAddUserForm()">Add User</button>
            <button onclick="loadUsers(), toggleUserTable()">Manage Users</button>
        </div>

        <div id="addUserForm" class="form-section" style="display:none;">
            <h3>Create Admin User</h3>
            <form onsubmit="addUser(); return false;">
                <input type="text" id="newUsername" placeholder="Username" required style="max-width: 98%">
                <input type="email" id="newEmail" placeholder="Email" required style="max-width: 98%">
                <input type="password" id="newPassword" placeholder="Password" required style="max-width: 98%">
                <button type="submit">Save User</button>
                <button type="button" onclick="toggleAddUserForm()">Cancel</button>
            </form>
        </div>

        <div id="userTable">
            <table>
                <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <!-- User rows will be dynamically added here -->
                </tbody>
            </table>
        </div>
    </div>
    <!--Home Page-->
    <div id="homePage" class="content-section">
        <h2>Update Home Page</h2>

        <div class="button-group">
            <button onclick="showHomeSection('logo')">Logo</button>
            <button onclick="showHomeSection('socialLinks')">Social Links</button>
            <button onclick="showHomeSection('latestDevelopments')">Latest Developments</button>
            <button onclick="showHomeSection('imageSlider')">Image Slider</button>
            <button onclick="showHomeSection('visionMission')">Vision & Mission</button>
            <button onclick="showHomeSection('highlightingMotivatingTales')">Highlighting Motivating Tales</button>
            <button onclick="showHomeSection('contactInfo')">Contact Info</button>
        </div>

        <div id="logoSection" class="home-section">
            <h3>Update Logo</h3>
            <form action="update_logo.php" method="post" enctype="multipart/form-data">
                <div>
                    <label for="top_logo">Top Logo:</label>
                    <input type="file" name="logo_image" id="top_logo" accept="image/*">
                    <input type="hidden" name="position" value="top">
                    <button type="submit">Upload</button>
                </div>
            </form>
            <form action="update_logo.php" method="post" enctype="multipart/form-data">
                <div>
                    <label for="bottom_logo">Bottom Logo:</label>
                    <input type="file" name="logo_image" id="bottom_logo" accept="image/*">
                    <input type="hidden" name="position" value="bottom">
                    <button type="submit">Upload</button>
                </div>
            </form>
            <div id="currentLogos">
                <h4>Current Logos:</h4>
                <div id="topLogoPreview"></div>
                <div id="bottomLogoPreview"></div>
            </div>
        </div>

        <div id="socialLinksSection" class="home-section">
            <h3>Update Social Links</h3>
            <form id="socialLinksForm" method="POST">
                <label for="facebook">Facebook:</label>
                <input type="url" id="facebook" name="facebook">

                <label for="youtube">YouTube:</label>
                <input type="url" id="youtube" name="youtube">

                <label for="instagram">Instagram:</label>
                <input type="url" id="instagram" name="instagram">

                <button type="submit">Update</button>
            </form>
        </div>

        <div id="latestDevelopmentsSection" class="home-section">
            <h3>Latest Developments</h3>
            <div class="button-group">
                <button onclick="showLatestDevelopmentsForm()">Add New Entry</button>
                <button onclick="showLatestDevelopmentsTable()">Manage Entries</button>
            </div>

            <div id="addLatestDevelopmentForm" style="display: none;">
                <h4>Add New Development</h4>
                <form id="newDevelopmentForm">
                    <div>
                        <label for="headline">Headline:</label>
                        <input type="text" id="headline" name="headline" required style="max-width: 98%">
                    </div>
                    <div>
                        <label for="link">Link:</label>
                        <input type="url" id="link" name="link" required style="max-width: 98%">
                    </div>
                    <button type="submit">Add Entry</button>
                </form>
            </div>

            <div id="manageLatestDevelopments" style="display: none;">
                <h4>Manage Entries</h4>
                <table>
                    <thead>
                    <tr>
                        <th>Headline</th>
                        <th>Link</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Entries will be populated here dynamically -->
                    </tbody>
                </table>
            </div>
        </div>

        <div id="imageSliderSection" class="home-section">
            <h3>Image Slider</h3>
            <div id="sliderImages"></div>
            <button onclick="addSliderImage()">Add Image</button>
        </div>

        <div id="visionMissionSection" class="home-section">
            <h3>Vision & Mission</h3>
            <div class="vision-section">
                <h4>Our Vision (English)</h4>
                <textarea id="visionEn"></textarea>
                <h4>Our Vision (Bangla)</h4>
                <textarea id="visionBn"></textarea>
            </div>
            <div class="mission-section">
                <h4>Mission Statement (English)</h4>
                <textarea id="missionEn"></textarea>
                <h4>Mission Statement (Bangla)</h4>
                <textarea id="missionBn"></textarea>
            </div>
            <button onclick="saveVisionMission()">Save</button>
        </div>

        <div id="highlightingMotivatingTalesSection" class="home-section">
            <h3>Highlighting Motivating Tales</h3>

            <div class="button-group">
                <button onclick="showTaleSection('addTale')">Add New Tale</button>
                <button onclick="showTaleSection('manageTales')">Manage Tales</button>
            </div>

            <div id="addTaleSection" class="tale-section" style="display: none;">
                <h4>Add/Edit Tale</h4>
                <form id="taleForm" enctype="multipart/form-data">
                    <div>
                        <label for="taleImage">Tale Image:</label>
                        <input type="file" id="taleImage" name="taleImage" onchange="previewTaleImage(this)">
                        <img id="taleImagePreview" style="display:none; max-width: 200px;">
                    </div>
                    <div>
                        <label for="taleDescriptionEn">Description (English):</label>
                        <textarea id="taleDescriptionEn" name="taleDescriptionEn"></textarea>
                    </div>
                    <div>
                        <label for="taleDescriptionBn">Description (Bangla):</label>
                        <textarea id="taleDescriptionBn" name="taleDescriptionBn"></textarea>
                    </div>
                    <div>
                        <button type="submit">Add/Update</button>
                        <button type="button" onclick="cancelTaleEdit()">Cancel</button>
                    </div>
                </form>
            </div>

            <div id="manageTalesSection" class="tale-section">
                <h4>Manage Tales</h4>
                <div id="talesGallery"></div>
            </div>
        </div>

        <div id="contactInfoSection" class="home-section">
            <h3>Update Contact Info</h3>
            <form id="contactInfoForm">
                <div>
                    <label for="phoneNumber">Phone Number:</label>
                    <input type="text" id="phoneNumber" name="phone" required>
                </div>
                <div>
                    <label for="phoneLink">Phone Link:</label>
                    <input type="text" id="phoneLink" name="phone_link">
                </div>
                <div>
                    <label for="emailAddress">Email Address:</label>
                    <input type="email" id="emailAddress" name="email" required>
                </div>
                <div>
                    <label for="emailLink">Email Link:</label>
                    <input type="text" id="emailLink" name="email_link">
                </div>
                <button type="submit">Update</button>
            </form>
        </div>
    </div>

    <!--About Us-->
    <div id="aboutUs" class="content-section" style="display:none;">
        <h2>Update About Us Page</h2>
        <div class="form-section">
            <label for="aboutUsEnglish">About Us (English):</label>
            <div id="aboutUsEnglish"></div>

            <label for="aboutUsBangla">About Us (Bangla):</label>
            <div id="aboutUsBangla"></div>

            <button onclick="saveAboutUs()">Update</button>
        </div>
    </div>
    <!-- Projects/Programs-->
    <div id="projects" class="content-section">
        <h2>Manage Programs/Projects</h2>

        <div class="button-group">
            <button onclick="showProjectSection('addProject')">Add New Project</button>
            <button onclick="showProjectSection('manageProjects')">Manage Projects</button>
        </div>

        <div id="addProjectSection" class="project-section" style="display:none;">
            <h3>Add/Edit Project</h3>
            <form id="projectForm" enctype="multipart/form-data" class="form-section">
                <input type="hidden" id="existingImage" name="existingImage">
                <div>
                    <label for="projectImage">Project Image:</label>
                    <input type="file" id="projectImage" name="image" onchange="previewImage(this)" style="max-width: 98%">
                    <img id="imagePreview" style="display:none; max-width: 200px;">
                </div>
                <div>
                    <label for="titleEn">Title (English):</label>
                    <input type="text" id="titleEn" name="titleEn" required style="max-width: 98%">
                </div>
                <div>
                    <label for="shortDescEn">Short Description (English):</label>
                    <div id="shortDescEn"></div>
                </div>
                <div>
                    <label for="detailedDescEn">Detailed Description (English):</label>
                    <div id="detailedDescEn"></div>
                </div>
                <div>
                    <label for="titleBn">Title (Bangla):</label>
                    <input type="text" id="titleBn" name="titleBn" required style="max-width: 98%">
                </div>
                <div>
                    <label for="shortDescBn">Short Description (Bangla):</label>
                    <div id="shortDescBn"></div>
                </div>
                <div>
                    <label for="detailedDescBn">Detailed Description (Bangla):</label>
                    <div id="detailedDescBn"></div>
                </div>
                <button type="submit">Save</button>
                <button type="button" onclick="cancelProjectEdit()">Cancel</button>
            </form>
        </div>

        <div id="manageProjectsSection" class="project-section">
            <h3>Manage Projects</h3>
            <table id="projectsTable">
                <thead>
                <tr>
                    <th>Image</th>
                    <th>Title (English)</th>
                    <th>Title (Bangla)</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <!-- Project rows will be dynamically added here -->
                </tbody>
            </table>
        </div>
    </div>

    <!--Photos-->
    <div id="photos" class="home-section" style="display:none;">
        <h2>Manage Photos</h2>
        <div class="button-group">
            <button onclick="showPhotoSection('addPhoto')">Add Photo</button>
            <button onclick="showPhotoSection('managePhotos')">Manage Photos</button>
        </div>

        <div id="addPhotoSection" class="photo-section">
            <h3>Add New Photo</h3>
            <form id="photoForm" enctype="multipart/form-data">
                <input type="file" name="photo" id="photo" onchange="previewPhoto(this)">
                <img id="photoPreview" style="display:none; max-width: 200px;">
                <input type="text" name="caption" id="photoCaption" placeholder="Enter photo caption">
                <button type="submit">Upload Photo</button>
            </form>
        </div>

        <div id="managePhotosSection" class="photo-section" style="display:none;">
            <h3>Manage Photos</h3>
            <div id="photoGallery"></div>
        </div>
    </div>
    <!--Videos-->
    <div id="videos" class="home-section" style="display:none;">
        <h2>Manage Videos</h2>

        <div class="button-group">
            <button onclick="showVideoSection('addVideo')">Add Video</button>
            <button onclick="showVideoSection('manageVideos')">Manage Videos</button>
        </div>

        <div id="addVideoSection" class="video-section" style="display:none;">
            <h3>Add/Edit Video</h3>
            <form id="videoForm">
                <input type="hidden" id="videoId" name="id">
                <div>
                    <label for="videoUrl">YouTube Video Embed Code:</label>
                    <input type="text" id="videoUrl" name="youtube_url" required>
                </div>
                <div>
                    <label for="videoTitle">Video Title:</label>
                    <input type="text" id="videoTitle" name="title" required>
                </div>
                <div style="display: flex; align-items: center; gap: 4px">
                    <label for="videoDescription">Video Description:</label>
                    <textarea id="videoDescription" name="description"></textarea>
                </div>
                <button type="submit">Add/Update</button>
                <button type="button" onclick="cancelVideoEdit()">Cancel</button>
            </form>
        </div>

        <div id="manageVideosSection" class="video-section">
            <h3>Manage Videos</h3>
            <div id="videoGallery"></div>
        </div>
    </div>
    <!--Contact Us-->
    <div id="contactUs" class="content-section">
        <h2>Contact Us Management</h2>
        <div class="button-group">
            <button onclick="showContactSection('updateContent')">Update Content</button>
            <button onclick="showContactSection('messages')">Messages</button>
        </div>

        <div id="updateContentSection" class="contact-section">
            <h3>Update Contact Information</h3>
            <form id="contactUsForm" class="form-section">
                <div>
                    <label for="officeAddressEn">Office Address (English):</label>
                    <input id="officeAddressEn" name="officeAddressEn" rows="3" required style="max-width: 98%"></input>
                </div>
                <div>
                    <label for="officeAddressBn">Office Address (Bangla):</label>
                    <input id="officeAddressBn" name="officeAddressBn" rows="3" required style="max-width: 98%"></input>
                </div>
                <div>
                    <label for="googleMapEmbed">Google Map Embed Code:</label>
                    <input id="googleMapEmbed" name="googleMapEmbed" rows="3" required style="max-width: 98%"></input>
                </div>
                <div>
                    <label for="ngoMemberInfoEn">NGO Member Information (English):</label>
                    <textarea id="ngoMemberInfoEn" name="ngoMemberInfoEn"></textarea>
                </div>
                <div>
                    <label for="ngoMemberInfoBn">NGO Member Information (Bangla):</label>
                    <textarea id="ngoMemberInfoBn" name="ngoMemberInfoBn"></textarea>
                </div>
                <button type="submit">Save</button>
            </form>
        </div>

        <div id="messagesSection" class="contact-section" style="display:none;">
            <h3>User Messages</h3>
            <table id="messagesTable">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <!-- Messages will be populated here by JavaScript -->
                </tbody>
            </table>
        </div>

    <!-- Modal for viewing message details -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="messageDetails"></div>
        </div>
    </div>
</div>

<!-- Volunteer-->
<div id="volunteer" class="content-section" style="display:none;">
    <h2>Volunteer Applications</h2>
    <div class="button-group">
        <button onclick="showVolunteerSection('bangladeshi')">Bangladeshi</button>
        <button onclick="showVolunteerSection('international')">International</button>
    </div>

    <div id="bangladeshiSection" class="volunteer-section">
        <h3>Bangladeshi Volunteer Applications</h3>
        <table id="bangladeshiVolunteerTable">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <!-- Volunteer data will be populated here -->
            </tbody>
        </table>
    </div>

    <div id="internationalSection" class="volunteer-section" style="display:none;">
        <h3>International Volunteer Applications</h3>
        <table id="internationalVolunteerTable">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Country</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <!-- International volunteer data will be populated here -->
            </tbody>
        </table>
    </div>
    <!-- Volunteer Modal -->
    <div id="volunteerModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="volunteerDetails"></div>
        </div>
    </div>
</div>
</div>
<script>
    function toggleDropdown() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>
<script src="js/summernote.js"></script>
<script src="js/adminpanel.js"></script>
</body>
</html>