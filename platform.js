const input = document.getElementById("input");
const format = document.getElementById("format");

// Apply state from hash
const p = new URLSearchParams(location.hash.replace("#", ""));
if (p.has("i"))
{
	input.value = p.get("i");
}
if (p.has("f") && format)
{
	format.value = p.get("f");
}

// Keep hash in sync with state
const update_hash = function()
{
	const params = [];
	if (input.value)
	{
		params.push("i="+encodeURIComponent(input.value));
	}
	if (format && format.value && format.value != "Decimal (Signed)")
	{
		params.push("f="+encodeURIComponent(format.value));
	}
	history.replaceState({}, undefined, params.length ? `#${params.join("&")}` : location.pathname);
};
input.addEventListener("input", update_hash);
format?.addEventListener("input", update_hash);

// Utilities for Pluto code
function get_input()
{
	pluto_give_file("input", document.getElementById("input").value)
}

function get_output()
{
	pluto_give_file("output", document.getElementById("output").value)
}
