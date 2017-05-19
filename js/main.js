function hideDelivery(select)
{
    if(select.value == 0)
    {
        document.getElementById('address_area').style.display = "table-row";
    }
    else
    {
        document.getElementById('address_area').style.display = "none";
    }
}