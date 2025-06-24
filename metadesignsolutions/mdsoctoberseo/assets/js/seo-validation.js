// Add a log to confirm the script is loaded and parsed

// Function to update character count and validation status
function updateSeoValidation(field, minLength, maxLength) {
    const value = field.val();
    const length = value.length;
    const errorElement = field.closest('.form-group').find('.error-message');

    // Only show error if below minLength (not above maxLength)
    if (length < minLength && length > 0) {
        field.addClass('is-invalid');
        field.removeClass('is-valid');
        // errorElement.text(`Minimum length should be ${minLength} characters`);
        // errorElement.show();
    } else if (length >= minLength) {
        field.removeClass('is-invalid');
        field.addClass('is-valid');
        errorElement.hide();
    } else {
        field.removeClass('is-invalid');
        field.removeClass('is-valid');
        errorElement.hide();
    }
}

// Function to update character counter with color feedback
function updateCharacterCounter(field) {
    const counter = field.siblings('.character-counter');
    const fieldName = field.attr('name');
    let maxLength = 60;
    let minLength = 30;
    let isTitle = false;
    let isDesc = false;

    // Determine if this is a title or description field
    if (
        fieldName === 'seo_title' || fieldName === 'EntryRecord[seo_title]' ||
        fieldName === 'og_title' || fieldName === 'EntryRecord[og_title]' ||
        fieldName === 'twitter_title' || fieldName === 'EntryRecord[twitter_title]'
    ) {
        isTitle = true;
        maxLength = 60;
        minLength = 30;
    } else if (
        fieldName === 'seo_description' || fieldName === 'EntryRecord[seo_description]' ||
        fieldName === 'og_description' || fieldName === 'EntryRecord[og_description]' ||
        fieldName === 'twitter_description' || fieldName === 'EntryRecord[twitter_description]'
    ) {
        isDesc = true;
        maxLength = 160;
        minLength = 120;
    }

    const length = field.val().length;
    counter.text(`${length}/${maxLength} characters`);
    field.removeClass('seo-input-green seo-input-orange seo-input-red');

    // Color logic for title
    if (isTitle) {
        if (length < 30) {
            counter.css('color', 'orange');
            field.addClass('seo-input-orange');
        } else if (length <= 60) {
            counter.css('color', 'green');
            field.addClass('seo-input-green');
        } else if (length <= 70) {
            counter.css('color', 'orange');
            field.addClass('seo-input-orange');
        } else {
            counter.css('color', 'red');
            field.addClass('seo-input-red');
        }
    }
    // Color logic for description
    else if (isDesc) {
        if (length < 70) {
            counter.css('color', 'orange');
            field.addClass('seo-input-orange');
        } else if (length <= 155) {
            counter.css('color', 'green');
            field.addClass('seo-input-green');
        } else if (length <= 160) {
            counter.css('color', 'orange');
            field.addClass('seo-input-orange');
        } else {
            counter.css('color', 'red');
            field.addClass('seo-input-red');
        }
    }

    // Live update snippet preview
    const snippetTitle = document.getElementById('snippet-title-preview');
    const snippetDesc = document.getElementById('snippet-description-preview');
    if (snippetTitle && isTitle && (fieldName === 'seo_title' || fieldName === 'EntryRecord[seo_title]')) {
        snippetTitle.textContent = field.val();
    }
    if (snippetDesc && isDesc && (fieldName === 'seo_description' || fieldName === 'EntryRecord[seo_description]')) {
        snippetDesc.textContent = field.val();
    }
}

// Function to mirror SEO fields to OG and Twitter fields
function mirrorSeoFields(sourceField, sourceValue) {
    const fieldName = sourceField.attr('name');
    
    // Mirror SEO title to OG and Twitter titles
    if (fieldName === 'seo_title' || fieldName === 'EntryRecord[seo_title]') {
        // Update OG title
        const ogTitleField = $('input[name="og_title"], input[name="EntryRecord[og_title]"]');
        if (ogTitleField.length) {
            ogTitleField.val(sourceValue);
            updateCharacterCounter(ogTitleField);
        }
        
        // Update Twitter title
        const twitterTitleField = $('input[name="twitter_title"], input[name="EntryRecord[twitter_title]"]');
        if (twitterTitleField.length) {
            twitterTitleField.val(sourceValue);
            updateCharacterCounter(twitterTitleField);
        }
    }
    
    // Mirror SEO description to OG and Twitter descriptions
    if (fieldName === 'seo_description' || fieldName === 'EntryRecord[seo_description]') {
        // Update OG description
        const ogDescField = $('textarea[name="og_description"], textarea[name="EntryRecord[og_description]"]');
        if (ogDescField.length) {
            ogDescField.val(sourceValue);
            updateCharacterCounter(ogDescField);
        }
        
        // Update Twitter description
        const twitterDescField = $('textarea[name="twitter_description"], textarea[name="EntryRecord[twitter_description]"]');
        if (twitterDescField.length) {
            twitterDescField.val(sourceValue);
            updateCharacterCounter(twitterDescField);
        }
    }
}

// Initialize validation for all SEO fields
function initializeSeoValidation() {
    const titleFieldSelector = 'input[name="seo_title"], input[name="EntryRecord[seo_title]"]';
    const descriptionFieldSelector = 'textarea[name="seo_description"], textarea[name="EntryRecord[seo_description]"]';

    // Add error message elements after each SEO field
    $(titleFieldSelector + ', ' + descriptionFieldSelector).each(function() {
        const field = $(this);
        if (!field.siblings('.error-message').length) {
            field.after('<div class="error-message text-danger" style="display: none;"></div>');
        }
    });

    // Add character counter
    $(titleFieldSelector + ', ' + descriptionFieldSelector).each(function() {
        const field = $(this);
        const fieldName = field.attr('name');
        const maxLength = (fieldName === 'seo_title' || fieldName === 'EntryRecord[seo_title]') ? 60 : 160;
        const counterHtml = `<div class="character-counter text-muted small mt-1">0/${maxLength} characters</div>`;
        
        if (!field.siblings('.character-counter').length) {
            field.after(counterHtml);
        }
    });

    // Add real-time validation for SEO title with mirroring
    $(titleFieldSelector).off('input').on('input', function() {
        const minLength = 30;
        const maxLength = 60;
        const field = $(this);
        const value = field.val();
        
        updateSeoValidation(field, minLength, maxLength);
        updateCharacterCounter(field);
        mirrorSeoFields(field, value);
    });

    // Add real-time validation for SEO description with mirroring
    $(descriptionFieldSelector).off('input').on('input', function() {
        const minLength = 120;
        const maxLength = 160;
        const field = $(this);
        const value = field.val();
        
        updateSeoValidation(field, minLength, maxLength);
        updateCharacterCounter(field);
        mirrorSeoFields(field, value);
    });
}

// Wait for document ready
$(document).ready(function() {
    initializeSeoValidation();
});

// Re-initialize validation when the form is loaded dynamically
$(document).on('ajaxComplete', function() {
    setTimeout(initializeSeoValidation, 100);
});

// Also initialize when the form is shown
$(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function() {
    setTimeout(initializeSeoValidation, 100);
});

document.addEventListener('DOMContentLoaded', function() {
    const titleField = document.querySelector('[name="EntryRecord[seo_title]"]');
    const descriptionField = document.querySelector('[name="EntryRecord[seo_description]"]');
    const keywordsField = document.querySelector('[name="EntryRecord[seo_keywords]"]');
    const titleText = document.querySelector('.snippet-title');
    const descriptionText = document.querySelector('.snippet-description');
    const titleCount = document.querySelector('.counter-item .count.warning');
    const descriptionCount = document.querySelector('.counter-item .count.good');
    const scoreElement = document.querySelector('.score-value');
    const scoreCircle = document.querySelector('.score-circle');

    // Add keyword validation function
    function validateKeywords(value) {
        if (!value) return true; // Empty is allowed
        
        // Check total length
        if (value.length > 255) {
            return 'Keywords must not exceed 255 characters';
        }
        
        // Split by commas and validate each keyword
        const keywords = value.split(',').map(k => k.trim());
        
        // Check for empty keywords between commas
        if (keywords.some(k => k === '')) {
            return 'Empty keywords are not allowed (remove extra commas)';
        }
        
        // Check for special characters (allow letters, numbers, spaces, and hyphens)
        const invalidKeywords = keywords.filter(k => !/^[a-zA-Z0-9\s-]+$/.test(k));
        if (invalidKeywords.length > 0) {
            return `Invalid characters in keywords: ${invalidKeywords.join(', ')}`;
        }
        
        return true;
    }
    
    console.log('SEO Validation: Initializing event listeners');
    
    if (titleField) {
        titleField.addEventListener('input', function() {
            const titleLength = titleField.value.length;
            if (titleText) {
                titleText.textContent = titleField.value;
            }
            if (titleCount) {
                titleCount.textContent = `${titleLength}/60`;
                titleCount.classList.toggle('warning', titleLength < 30 || titleLength > 60);
                titleCount.classList.toggle('good', titleLength >= 30 && titleLength <= 60);
            }
            updateScore();
        });
    }
    
    if (descriptionField) {
        descriptionField.addEventListener('input', function() {
            console.log('Description field input event triggered');
            const descriptionLength = descriptionField.value.length;
            if (descriptionText) {
                descriptionText.textContent = descriptionField.value;
            }
            if (descriptionCount) {
                descriptionCount.textContent = `${descriptionLength}/160`;
                descriptionCount.classList.toggle('warning', descriptionLength < 120 || descriptionLength > 160);
                descriptionCount.classList.toggle('good', descriptionLength >= 120 && descriptionLength <= 160);
            }
            updateScore();
        });
    }

    if (keywordsField) {
        keywordsField.addEventListener('input', function() {
            const validation = validateKeywords(this.value);
            const errorElement = keywordsField.closest('.form-group').querySelector('.keywords-error');
            
            // Create error element if it doesn't exist
            if (!errorElement) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'keywords-error text-danger small mt-1';
                keywordsField.parentNode.appendChild(errorDiv);
            }
            
            if (validation !== true) {
                keywordsField.classList.add('is-invalid');
                keywordsField.classList.remove('is-valid');
                if (errorElement) {
                    errorElement.textContent = validation;
                    errorElement.style.display = 'block';
                }
            } else {
                keywordsField.classList.remove('is-invalid');
                keywordsField.classList.add('is-valid');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
                updateScore();
            }
        });
    }
});



// Listen for AJAX success on the form to refresh the SEO score partial
// Add a log to confirm the listener is attached

$(document).on('ajaxSuccess', 'form', function(event, data, status, xhr) {
    // Add a log to confirm the listener is triggered
    console.log('SEO Validation: ajaxSuccess listener triggered', event, data, status, xhr);

    // Check if the AJAX request was a save operation and was successful
    // You might need to adjust the condition based on your form's specific AJAX handling
    if (data && data.update && data.update['#seo-score-container']) {
        // The partial was already updated by the backend, no need to do anything
        // This condition might need adjustment depending on backend AJAX response
        console.log('SEO Validation: Partial already updated by backend');
        return;
    }

    // Find the form and get model details
    const form = $(this);
    const modelId = form.find('input[name="id"]').val(); // Assuming ID field name is 'id'
    const modelClass = form.find('input[name="_model_class"]').val(); // Assuming a hidden field stores model class

    // If model details are found, trigger the AJAX partial refresh
    if (modelId && modelClass) {
        console.log('SEO Validation: Model details found, triggering partial refresh');
        // Construct the data to send to the AJAX handler
        const postData = {
            model_id: modelId,
            model_class: modelClass
        };

        // Trigger the AJAX request to refresh the SEO score partial
        // The handler name is the protected method name prefixed with on,
        // and the controller alias might be needed depending on setup.
        // Assuming the controller is accessed via /backend/metadesignsolutions/mdsoctoberseo/seo
        // and the handler is protected function onRefreshSeoScore()
        form.request('onRefreshSeoScore', {
            data: postData,
            update: {
                '$/metadesignsolutions/mdsoctoberseo/models/_seo_score.htm': '#seo-score-container'
            },
             error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error refreshing SEO score partial:', textStatus, errorThrown);
            },
            success: function() {
                console.log('SEO Validation: Partial refresh AJAX request successful');
            }
        });
    } else {
        console.log('SEO Validation: Model details not found, cannot trigger partial refresh');
    }
});


// Add image upload listener
function observeImageUpload() {
    function tryAttachObserver() {
        // Use the correct selector for the MediaFinder widget
        const mediaFinderWidget = document.querySelector('[data-control="mediafinder"]');
        if (mediaFinderWidget) {
            console.log('Attaching MutationObserver to [data-control="mediafinder"]');
            const observer = new MutationObserver(function(mutationsList, observer) {
                console.log('MutationObserver callback triggered');
                const filesContainer = mediaFinderWidget.querySelector('.mediafinder-files-container');
                if (!filesContainer) {
                    console.log('No .mediafinder-files-container found');
                    return;
                }
                const imageObject = filesContainer.querySelector('.item-object.item-object-image');
                if (imageObject) {
                    // Use data-path attribute as per your HTML structure
                    const imagePath = imageObject.getAttribute('data-path');
                    if (imagePath && imagePath.trim() !== '') {
                        console.log('Image detected! Path:', imagePath);
                    } else {
                        console.log('Image object found, but no valid data-path');
                    }
                } else {
                    console.log('No .item-object.item-object-image found');
                }
                updateScore();
            });

            observer.observe(mediaFinderWidget, {
                childList: true,
                subtree: true
            });
        } else {
            // Try again in 200ms if not found
            setTimeout(tryAttachObserver, 200);
        }
    }
    tryAttachObserver();
}

// Call on DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    observeImageUpload();
});
// Also call after AJAX and tab changes
$(document).on('ajaxComplete', function() {
    setTimeout(observeImageUpload, 100);
});
$(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function() {
    setTimeout(observeImageUpload, 100);
});


// Move updateScore to global scope
function updateScore() {
    console.log('updateScore called');
    let score = 0;
    let suggestions = [];

    // Title fields (SEO, OG, Twitter)
    const titleValue = getFieldValue([
        '[name="EntryRecord[seo_title]"]', '[name="seo_title"]',
        '[name="EntryRecord[og_title]"]', '[name="og_title"]',
        '[name="EntryRecord[twitter_title]"]', '[name="twitter_title"]'
    ]);
    console.log('Title value:', titleValue);
    if (titleValue) {
        const titleLength = titleValue.length;
        if (titleLength >= 30 && titleLength <= 60) {
            score += 20;
        } else {
            suggestions.push('Title length should be between 30-60 characters (SEO, OG, or Twitter)');
        }
    } else {
        suggestions.push('Add a title (SEO, OG, or Twitter) for better analysis');
    }

    // Description fields (SEO, OG, Twitter)
    const descValue = getFieldValue([
        '[name="EntryRecord[seo_description]"]', '[name="seo_description"]',
        '[name="EntryRecord[og_description]"]', '[name="og_description"]',
        '[name="EntryRecord[twitter_description]"]', '[name="twitter_description"]'
    ]);
    console.log('Description value:', descValue);
    if (descValue) {
        const descLength = descValue.length;
        if (descLength >= 120 && descLength <= 160) {
            score += 20;
        } else {
            suggestions.push('Description length should be between 120-160 characters (SEO, OG, or Twitter)');
        }
    } else {
        suggestions.push('Add a description (SEO, OG, or Twitter) for analysis');
    }

    // Only check for og_image (both possible field names)
    const imageValue = getFieldValue([
        '[name="EntryRecord[og_image]"]', '[name="og_image"]'
    ]);
    // Fallback: check for image in mediafinder widget
    let imageVal = imageValue;
    if (!imageVal) {
        const filesContainer = document.querySelector('.mediafinder-files-container');
        if (filesContainer) {
            const existingImage = filesContainer.querySelector('img[src], img[data-thumb-url]');
            if (existingImage) {
                imageVal = 'mediafinder';
            }
        }
    }
    console.log('Image value:', imageVal);
    if (imageVal) {
        score += 20;
    } else {
        suggestions.push('Add an image (OG) for analysis');
    }

    // Keywords
    const keywordsValue = getFieldValue([
        '[name="EntryRecord[seo_keywords]"]', '[name="seo_keywords"]'
    ]);
    console.log('Keywords value:', keywordsValue);
    if (keywordsValue) {
        const keywords = keywordsValue.split(',');
        if (keywords.length >= 1) {
            score += 15;
        } else {
            suggestions.push('Add at least one keyword');
        }
    } else {
        suggestions.push('Add keywords for analysis');
    }

    // OG fields present (og_title, og_description, og_image, og_type)
    const ogTitleVal = getFieldValue(['[name="EntryRecord[og_title]"]', '[name="og_title"]']);
    const ogDescVal = getFieldValue(['[name="EntryRecord[og_description]"]', '[name="og_description"]']);
    const ogImageVal = getFieldValue(['[name="EntryRecord[og_image]"]', '[name="og_image"]']);
    const ogTypeVal = getFieldValue(['[name="EntryRecord[og_type]"]', '[name="og_type"]']);
    console.log('OG title:', ogTitleVal);
    console.log('OG desc:', ogDescVal);
    console.log('OG image:', ogImageVal);
    console.log('OG type:', ogTypeVal);
    const ogFieldsPresent = ogTitleVal && ogDescVal && ogImageVal && ogTypeVal;
    console.log('OG fields present:', ogFieldsPresent);
    if (ogFieldsPresent) {
        score += 15;
    } else {
        suggestions.push('Complete all Open Graph fields for best social sharing');
    }

    // Twitter fields present (twitter_title, twitter_description, twitter_card)
    const twTitleVal = getFieldValue(['[name="EntryRecord[twitter_title]"]', '[name="twitter_title"]']);
    const twDescVal = getFieldValue(['[name="EntryRecord[twitter_description]"]', '[name="twitter_description"]']);
    const twCardVal = getFieldValue(['[name="EntryRecord[twitter_card]"]', '[name="twitter_card"]']);
    console.log('Twitter title:', twTitleVal);
    console.log('Twitter desc:', twDescVal);
    console.log('Twitter card:', twCardVal);
    const twitterFieldsPresent = twTitleVal && twDescVal && twCardVal;
    console.log('Twitter fields present:', twitterFieldsPresent);
    if (twitterFieldsPresent) {
        score += 10;
    } else {
        suggestions.push('Complete all Twitter Card fields for best sharing on X/Twitter');
    }

    // Clamp score to 100
    score = Math.min(score, 100);
    console.log('Final score:', score);

    // Update the score display
    const scoreValue = document.querySelector('.score-value');
    const scoreLabel = document.querySelector('.score-label');
    const scoreCircle = document.querySelector('.score-circle');
    const scoreFeedback = document.querySelector('.score-feedback ul');

    if (scoreValue) scoreValue.textContent = score;

    // Update score label and color based on score ranges
    let scoreColor = '#dc3545'; // Default red for poor
    let labelText = 'Poor';

    if (score === 100) {
        scoreColor = '#007bff'; // Blue for Excellent
        labelText = 'Excellent';
    } else if (score >= 75) {
        scoreColor = '#28a745'; // Green
        labelText = 'Good';
    } else if (score >= 50) {
        scoreColor = '#ffc107'; // Yellow
        labelText = 'Fair';
    } else if (score >= 25) {
        scoreColor = '#fd7e14'; // Orange
        labelText = 'Needs Improvement';
    }

    if (scoreLabel) scoreLabel.textContent = labelText;
    if (scoreCircle) scoreCircle.style.background = scoreColor;

    // Update suggestions
    if (scoreFeedback) {
        scoreFeedback.innerHTML = suggestions.map(suggestion => `<li>✔ ${suggestion}</li>`).join('');
    }

    // Update counters in the character-counters section
    const titleCountSpan = document.querySelector('.counter-item:first-child .count');
    const descriptionCountSpan = document.querySelector('.counter-item:last-child .count');

    if (titleCountSpan) {
        titleCountSpan.textContent = titleValue.length + '/60';
        titleCountSpan.classList.toggle('warning', titleValue.length < 30 || titleValue.length > 60);
        titleCountSpan.classList.toggle('good', titleValue.length >= 30 && titleValue.length <= 60);
    }

    if (descriptionCountSpan) {
        descriptionCountSpan.textContent = descValue.length + '/160';
        descriptionCountSpan.classList.toggle('warning', descValue.length < 120 || descValue.length > 160);
        descriptionCountSpan.classList.toggle('good', descValue.length >= 120 && descValue.length <= 160);
    }
}

function getFieldValue(selectors) {
    for (let i = 0; i < selectors.length; i++) {
        const el = document.querySelector(selectors[i]);
        if (el && el.value.trim()) {
            return el.value.trim();
        }
    }
    return '';
}

// Add color logic and listeners for Social Media fields
function addSocialMediaFieldListeners() {
    const ogFields = [
        '[name="EntryRecord[og_title]"]', '[name="og_title"]',
        '[name="EntryRecord[og_description]"]', '[name="og_description"]',
        '[name="EntryRecord[og_image]"]', '[name="og_image"]',
        '[name="EntryRecord[og_type]"]', '[name="og_type"]'
    ];
    const twitterFields = [
        '[name="EntryRecord[twitter_title]"]', '[name="twitter_title"]',
        '[name="EntryRecord[twitter_description]"]', '[name="twitter_description"]',
        '[name="EntryRecord[twitter_card]"]', '[name="twitter_card"]'
    ];
    ogFields.concat(twitterFields).forEach(function(selector) {
        const el = document.querySelector(selector);
        if (el) {
            el.addEventListener('input', function() {
                updateCharacterCounter($(el));
                updateScore();
            });
            el.addEventListener('change', function() {
                updateCharacterCounter($(el));
                updateScore();
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    addSocialMediaFieldListeners();
});
$(document).on('ajaxComplete', function() {
    setTimeout(addSocialMediaFieldListeners, 100);
});
$(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function() {
    setTimeout(addSocialMediaFieldListeners, 100);
});

// Add these styles to your CSS (or inject via JS if needed)
(function() {
    var style = document.createElement('style');
    style.innerHTML = `
    .seo-input-green {
        border-color: #28a745 !important;
        background: #eafaf1 !important;
    }
    .seo-input-orange {
        border-color: #ffc107 !important;
        background: #fffbe6 !important;
    }
    .seo-input-red {
        border-color: #dc3545 !important;
        background: #fff0f0 !important;
    }
    `;
    document.head.appendChild(style);
})();