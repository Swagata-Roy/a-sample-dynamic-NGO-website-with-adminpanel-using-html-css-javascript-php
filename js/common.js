//Logos
fetch('get_logos.php')
    .then(response => response.json())
    .then(data => {
        document.getElementById('top-logo').src = data.top;
        document.getElementById('bottom-logo').src = data.bottom;
    })
    .catch(error => console.error(error));

//Contact Info
fetch('get_contact_info.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const contactInfo = data.data;

            // Update top contact info
            updateContactLink('phonetop', contactInfo.phone, contactInfo.phone_link);
            updateContactLink('emailtop', contactInfo.email, contactInfo.email_link);

            // Update footer contact info
            updateContactLink('phonefooter', contactInfo.phone, contactInfo.phone_link);
            updateContactLink('emailfooter', contactInfo.email, contactInfo.email_link);
        } else {
            console.error('Error fetching contact info:', data.message);
        }
    })
    .catch(error => console.error(error));

function updateContactLink(elementId, text, link) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = `<a href="${link}" class="phoneAndEmail">${text}</a>`;
    }
}

//Social Links
function updateSocialLinks() {
    fetch('update_social_links.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const platforms = ['facebook', 'youtube', 'instagram'];
                platforms.forEach(platform => {
                    const topLink = document.getElementById(`${platform}-link-top`);
                    const bottomLink = document.getElementById(`${platform}-link-bottom`);
                    if (topLink && bottomLink && data.data[platform]) {
                        topLink.href = data.data[platform];
                        bottomLink.href = data.data[platform];
                    }
                });
            } else {
                console.error('Error fetching social links:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Call the function to update social links when the page loads
document.addEventListener('DOMContentLoaded', updateSocialLinks);