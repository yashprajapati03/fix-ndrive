<?php
session_start();
include 'connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix N Drive - Auto Repair & Service Center</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <header id="header">
        <div class="container header-content">
            <div class="logo">
                <img src="Logo.png" alt="Fix N Drive Logo">
                <h1>Fix N <span>Drive</span></h1>
            </div>
            <nav>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#job-card">Job Card</a></li>
                    <li><a href="#footer">About</a></li>
                    <li><a href="#footer">Contact</a></li>
                    <li><a href="#history">History</a></li>
                    <?php if (isset($_SESSION['username'])): ?>
                    <li><a href="logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a></li>
                    <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero" id="home">
        <div class="container">
            <?php if (isset($_SESSION['username'])): ?>
            <h3 style="color: #e74c3c; margin-bottom: 10px;">Welcome back, <?php echo $_SESSION['username']; ?>!</h3>
            <?php endif; ?>
            <h2>Professional Auto Repair Services</h2>
            <p>Your trusted partner for all vehicle maintenance and repair needs. We provide high-quality service with a
                focus on customer satisfaction.</p>
            <a href="#services" class="btn">Our Services</a>
            <a href="#job-card" class="btn btn-outline">Generate Job Card</a>
        </div>
    </section>

    <section class="features" id="services">
        <div class="container">
            <div class="section-title">
                <h2>Our Services</h2>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🔧</div>
                    <h3>General Maintenance</h3>
                    <p>Regular check-ups and maintenance to keep your vehicle running smoothly and prevent future
                        problems.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🚗</div>
                    <h3>Engine Repair</h3>
                    <p>Expert diagnosis and repair of all engine issues, from minor adjustments to complete overhauls.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Electrical Systems</h3>
                    <p>Comprehensive electrical system services including battery, alternator, and wiring repairs.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🛑</div>
                    <h3>Brake Services</h3>
                    <p>Complete brake inspection, repair, and replacement to ensure your safety on the road.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🛞 </div>
                    <h3>Tire Services</h3>
                    <p>Tire rotation, balancing, alignment, and replacement services for optimal performance.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🛢️</div>
                    <h3>Oil Change</h3>
                    <p>Quick and efficient oil change services with high-quality lubricants for your engine's longevity.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="user-history" id="history" style="padding: 4rem 0; background: #fff;">
        <div class="container">
            <div class="section-title">
                <h2>Your Recent Job Cards</h2>
            </div>
            <?php if (isset($_SESSION['username'])): ?>
            <div class="history-table-wrapper" style="overflow-x: auto;">
                <table class="history-table"
                    style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <thead>
                        <tr style="background: #2c3e50; color: white;">
                            <th style="padding: 15px; text-align: left;">Date</th>
                            <th style="padding: 15px; text-align: left;">Vehicle</th>
                            <th style="padding: 15px; text-align: left;">Technician</th>
                            <th style="padding: 15px; text-align: left;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $currentUser = $_SESSION['username'];
                        $query = "SELECT * FROM job_cards WHERE created_by = '$currentUser' ORDER BY date DESC LIMIT 5";
                        $result = $conn->query($query);

                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr style='border-bottom: 1px solid #ddd;'>
                                        <td style='padding: 12px;'>".htmlspecialchars($row['date'])."</td>
                                        <td style='padding: 12px;'>".htmlspecialchars($row['vehicle_make'] . " " . $row['vehicle_model'])."</td>
                                        <td style='padding: 12px;'>".htmlspecialchars($row['technician'])."</td>
                                        <td style='padding: 12px;'><span style='color: green; font-weight: bold;'>Saved</span></td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' style='padding: 20px; text-align: center;'>No history found. Generate your first card below!</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p style="text-align: center;">Please <a href="login.php"
                    style="color: #e74c3c; font-weight: bold;">Login</a> to view your history.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="job-card-generator" id="job-card">
        <div class="container">
            <h2>Job Card Generator</h2>
            <p>Create a professional job card for your vehicle service or repair</p>

            <div class="generator-form">
                <form id="jobCardForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="customerName">Customer Name</label>
                            <input type="text" id="customerName" required>
                        </div>
                        <div class="form-group">
                            <label for="contactNumber">Contact Number</label>
                            <input type="tel" id="contactNumber" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="vehicleMake">Vehicle Name</label>
                            <input type="text" id="vehicleMake" required>
                        </div>
                        <div class="form-group">
                            <label for="vehicleModel">Model</label>
                            <input type="text" id="vehicleModel" required>
                        </div>
                        <div class="form-group">
                            <label for="vehicleYear">Year</label>
                            <input type="number" id="vehicleYear" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="vehicleNumber">Vehicle Registration Number</label>
                        <input type="text" id="vehicleNumber" required>
                    </div>

                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" id="date" required>
                    </div>

                    <div class="form-group">
                        <label>Services Required</label>
                        <div class="services-list" id="servicesList">
                            <!-- Services will be added here -->
                        </div>
                        <button type="button" class="btn add-service" id="addService">+ Add Service</button>
                    </div>

                    <div class="form-group">
                        <label for="technician">Assigned Technician</label>
                        <select id="technician" required>
                            <option value="">Select Technician</option>
                            <option value="Kamlesh">Kamlesh</option>
                            <option value="Bhadresh">Bhadresh</option>
                            <option value="Suresh">Suresh</option>
                            <option value="Mahesh">Mahesh</option>
                            <option value="Vishwesh">Vishwesh</option>
                            <option value="Naresh">Naresh</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="notes">Additional Notes</label>
                        <textarea id="notes" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn" style="width: 100%;">Generate Job Card</button>
                </form>
            </div>
        </div>
    </section>

    <footer id="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Fix N Drive</h3>
                    <p>Your trusted auto repair and service center providing quality maintenance and repair services
                        since 2010.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#job-card">Job Card</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact Us</h3>
                    <ul>
                        <li>Fix n Drive, Anant Chowk</li>
                        <li>Damodar Nagar, Ahmedabad</li>
                        <li>Phone: +91 9313073099</li>
                        <li>Email: info@fixndrive.com</li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2023 Fix N Drive. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
    // User Authentication Functionality (updated)
    document.addEventListener("DOMContentLoaded", function() {
        const username = localStorage.getItem("username");
        const loginLink = document.getElementById("loginLink");
        const logoutLink = document.getElementById("logoutLink");

        if (username) {
            // Show logout, hide login
            loginLink.style.display = "none";
            logoutLink.style.display = "inline";

            // Optionally show username on navbar
            const userSpan = document.createElement("span");
            userSpan.textContent = `Hi, ${username}`;
            userSpan.style.color = "#fff";
            userSpan.style.marginLeft = "10px";
            logoutLink.parentElement.insertBefore(userSpan, logoutLink);
        }

        // Logout click
        logoutLink.addEventListener("click", function() {
            localStorage.removeItem("username");
            alert("Logged out successfully!");
            location.reload();
        });
    });
    // Job Card Generator Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const addServiceBtn = document.getElementById('addService');
        const servicesList = document.getElementById('servicesList');
        let serviceCount = 0;

        // Add service field
        addServiceBtn.addEventListener('click', function() {
            serviceCount++;
            const serviceItem = document.createElement('div');
            serviceItem.className = 'service-item';
            serviceItem.innerHTML = `
                    <input type="text" id="service${serviceCount}" placeholder="Service description" required>
                    <button type="button" class="btn remove-service">Remove</button>
                `;
            servicesList.appendChild(serviceItem);

            // Add remove functionality
            serviceItem.querySelector('.remove-service').addEventListener('click', function() {
                servicesList.removeChild(serviceItem);
            });
        });

        // Form submission
        const jobCardForm = document.getElementById('jobCardForm');
        jobCardForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Collect form data
            const formData = {
                customerName: document.getElementById('customerName').value,
                contactNumber: document.getElementById('contactNumber').value,
                vehicleMake: document.getElementById('vehicleMake').value,
                vehicleModel: document.getElementById('vehicleModel').value,
                vehicleYear: document.getElementById('vehicleYear').value,
                vehicleNumber: document.getElementById('vehicleNumber').value,
                date: document.getElementById('date').value,
                technician: document.getElementById('technician').value,
                notes: document.getElementById('notes').value,
                services: []
            };

            // Collect services
            const serviceInputs = servicesList.querySelectorAll('input[type="text"]');
            serviceInputs.forEach(input => {
                if (input.value.trim() !== '') {
                    formData.services.push(input.value.trim());
                }
            });

            // Combine services into a single string
            const servicesStr = formData.services.join(', ');

            // Create form and submit via POST
            const postForm = document.createElement('form');
            postForm.method = 'POST';
            postForm.action = 'jobcard.php';

            // Add form fields
            for (const key in formData) {
                if (key === 'services') formData[key] = servicesStr;
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = formData[key];
                postForm.appendChild(input);
            }

            document.body.appendChild(postForm);
            postForm.submit();
        });

        // Set today's date as default
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date').value = today;
    });
    </script>
</body>

</html>