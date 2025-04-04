<?php
// Fetch Data from API
function getDeathData() {
    $apiUrl = "https://civilregistrar.lgu2.com/api/death.php"; // Change to the correct API endpoint

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        die("Error: Unable to fetch data from API");
    }

    return json_decode($response, true);
}

$deathRecords = getDeathData();
?>

<div class="death-container" style="max-width: 100%; padding: 20px; margin: 20px 0; background: #fff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <h1 class="death-title" style="font-size: 2rem; font-weight: bold; text-align: center; color: #B91C1C; margin-bottom: 20px;">
        Death Certificate Records
    </h1>

    <?php if (empty($deathRecords)): ?>
        <p class="no-records" style="text-align: center; color: #DC2626; font-weight: bold;">
            No records found.
        </p>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="death-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">ID</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Deceased Name</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Date of Birth</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Date of Death</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Place of Death</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Cause of Death</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Informant</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Relationship</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Contact</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Method of Disposition</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Disposition Date</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Disposition Location</th>
                        <th style="padding: 12px; border: 1px solid #ccc; background: #F3F4F6; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deathRecords as $record): ?>
                        <tr>
                            <td style="padding: 12px; border: 1px solid #ccc; text-align: center;">
                                <?= htmlspecialchars($record['id']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc;">
                                <?= htmlspecialchars($record['deceased_first_name'] . ' ' . $record['deceased_middle_name'] . ' ' . $record['deceased_last_name']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc; text-align: center;">
                                <?= htmlspecialchars($record['deceased_dob']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc; text-align: center;">
                                <?php 
                                    $dateOfDeath = new DateTime($record['date_of_death']);
                                    echo $dateOfDeath->format('F d, Y'); 
                                ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc;">
                                <?= htmlspecialchars($record['place_of_death']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc;">
                                <?= htmlspecialchars($record['cause_of_death']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc;">
                                <?= htmlspecialchars($record['informant_name']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc;">
                                <?= htmlspecialchars($record['relationship_to_deceased']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc; text-align: center;">
                                <?= htmlspecialchars($record['informant_contact']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc;">
                                <?= htmlspecialchars($record['disposition_method']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc; text-align: center;">
                                <?php 
                                    $dispositionDate = new DateTime($record['disposition_date']);
                                    echo $dispositionDate->format('F d, Y'); 
                                ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc;">
                                <?= htmlspecialchars($record['disposition_location']); ?>
                            </td>
                            <td style="padding: 12px; border: 1px solid #ccc; text-align: center; font-weight: bold; <?= ($record['status'] === 'completed') ? 'color: #16A34A;' : 'color: #DC2626;'; ?>">
                                <?= htmlspecialchars($record['status']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
