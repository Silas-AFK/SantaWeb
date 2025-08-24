 // side barer menue
function toggleSidebar() {
    document.getElementById("sidebar").style.width = "250px";
  }
  
  function closeSidebar() {
    document.getElementById("sidebar").style.width = "0";
  }
  
   // Contact on  WhatsApp number

   const form = document.getElementById('contact-form');
   const nameInput = document.getElementById('name');
   const messageInput = document.getElementById('message');
 
   form.addEventListener('submit', function(e) {
     e.preventDefault();
     const name = nameInput.value.trim();
     const message = messageInput.value.trim();
     
     // Reset borders
     nameInput.style.borderColor = '#ccc';
     messageInput.style.borderColor = '#ccc';
 
     if (!name || !message) {
       if (!name) nameInput.style.borderColor = 'red';
       if (!message) messageInput.style.borderColor = 'red';
       alert("⚠️ Please fill in both your Name and Message.");
       return;
     }

     alert("✅ Message ready to send via WhatsApp!");
 
     const phoneNumber = "237653625962"; // your WhatsApp number
     const url = `https://wa.me/${phoneNumber}?text=Hello%20SMCC%20Santa,%0AMy%20name%20is%20${encodeURIComponent(name)}.%0A${encodeURIComponent(message)}`;
     window.open(url, '_blank');
   });

//Testimonial section
let currentTestimonial = 0;
const testimonials = document.querySelectorAll('.testimonial-slide');

function showTestimonial(index) {
  testimonials.forEach((slide, i) => {
    slide.classList.remove('active');
    if (i === index) {
      slide.classList.add('active');
    }
  });
}

function nextTestimonial() {
  currentTestimonial = (currentTestimonial + 1) % testimonials.length;
  showTestimonial(currentTestimonial);
}

function prevTestimonial() {
  currentTestimonial = (currentTestimonial - 1 + testimonials.length) % testimonials.length;
  showTestimonial(currentTestimonial);
}

// Optional: Auto-slide every 5 seconds
setInterval(nextTestimonial, 5000);


