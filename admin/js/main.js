/**
 * Created by arozhkov on 09.08.17.
 */

function deletePosition(elem)
{
	var orders = document.getElementById('admin_orders');
	var numRows = orders.getElementsByTagName('tr').length;

	if(numRows > 1)
	{
		elem.parentNode.removeChild(elem);
	}

	return false;
}

function addPosition()
{
	var orders = document.getElementById('admin_orders');
	var tr = orders.getElementsByTagName('tr');
	tr =  tr[tr.length - 1];
	var lastNumber = Number(tr.getElementsByTagName('select')[0].getAttribute('name').substr('products_'.length));
	lastNumber++;
	var newElement = tr.cloneNode(true);
	orders.appendChild(newElement);
	var newElemSelect = newElement.getElementsByTagName('select')[0];
	var newElemInput = newElement.getElementsByTagName('input')[0];

	newElemSelect.setAttribute('name', 'products_' + lastNumber);
	newElemInput.setAttribute('value', 1);
	newElemInput.setAttribute('name', 'count_' + lastNumber);

	return false;
}